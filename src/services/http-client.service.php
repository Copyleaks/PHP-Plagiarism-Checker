<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Sends the SDK's HTTP requests with curl. One curl handle is reused for the whole process, so connections stay
 * open between calls (keep-alive, no new TCP and TLS handshake). Requests have a connect and a total timeout,
 * see CopyleaksConfig::SET_CONNECT_TIMEOUT() and CopyleaksConfig::SET_TIMEOUT().
 */
class HttpClientService {
  /** @var resource|\CurlHandle|null */
  private static $handle = null;

  /** @var int|null The process that created the handle. A forked child gets its own handle. */
  private static $handlePid = null;

  /**
   * @param string $verb GET, POST (form), POST-JSON, PUT, PATCH (JSON) or MULTIPART (fields array, \CURLFile files).
   * @param string $url
   * @param array $headers Header lines ('Name: value') or a 'Name' => 'value' map.
   * @param mixed $data Request body, or null.
   * @return mixed Decoded JSON, the raw response text when it is not JSON, or null.
   * @throws CommandException
   * @throws RateLimitException
   * @throws UnderMaintenanceException
   * @throws \JsonException When the request body cannot be encoded as JSON.
   */
  public static function Execute(string $verb, string $url, array $headers, $data = null) {
    $options = self::buildOptions($verb, $url, $headers, $data);

    $responseHeaders = [];
    $options[CURLOPT_HEADERFUNCTION] = static function ($ch, string $line) use (&$responseHeaders): int {
      $trimmed = trim($line);
      if (stripos($trimmed, 'HTTP/') === 0) {
        // A new status line (for example after "100 Continue"): keep only the final response's headers.
        $responseHeaders = [];
      } elseif ($trimmed !== '' && strpos($trimmed, ':') !== false) {
        [$name, $value] = explode(':', $trimmed, 2);
        $responseHeaders[strtolower(trim($name))] = trim($value);
      }
      return strlen($line);
    };

    $ch = self::handle();
    try {
      curl_setopt_array($ch, $options);
      $result = curl_exec($ch);
      $statusCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
      $contentType = (string) curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
      $curlErrno = curl_errno($ch);
      $curlError = curl_error($ch);
      $curlInfo = curl_getinfo($ch);
    } finally {
      // Clears this request's options and its copy of the request body; open connections are kept.
      curl_reset($ch);
    }

    $body = is_string($result) ? $result : null;

    if (isSuccessStatusCode($statusCode)) {
      if ($result === false || $curlErrno !== 0) {
        // The status line arrived but the body did not (timeout, dropped connection or a corrupt body).
        throw new CommandException(self::errorMessage($body, $curlInfo, $curlError), 0, null, $body, $responseHeaders);
      }
      if (strpos($contentType, 'application/json') === false) {
        return $result;
      }
      if ($result === '') {
        return null;
      }
      try {
        return json_decode($result, false, 512, JSON_THROW_ON_ERROR);
      } catch (\JsonException $e) {
        throw new CommandException(self::errorMessage($body, $curlInfo, 'Invalid JSON in the response: ' . $e->getMessage()), $statusCode, $e, $body, $responseHeaders);
      }
    } elseif (isUnderMaintenanceResponse($statusCode)) {
      throw new UnderMaintenanceException($statusCode, null, $body, $responseHeaders);
    } elseif (isRateLimitResponse($statusCode)) {
      throw new RateLimitException($statusCode, null, $body, $responseHeaders);
    } else {
      throw new CommandException(self::errorMessage($body, $curlInfo, $curlError), $statusCode, null, $body, $responseHeaders);
    }
  }

  private static function errorMessage(?string $result, $curlInfo, string $curlError): string
  {
    $err_message = "\n" . "----Copyleaks SDK Error-----" . "\n\n";
    if (isset($result) && $result != "") {
      $err_message = $err_message . "result:" . "\n" . json_encode($result) . "\n\n";
    }
    if (isset($curlInfo)) {
      $err_message = $err_message . "info:" . "\n" .  json_encode($curlInfo) . "\n\n";
    }
    if ($curlError != "") {
      $err_message = $err_message . "error:" . "\n" . json_encode($curlError) . "\n\n";
    }
    return $err_message . "----------------------------\n";
  }

  /**
   * Close the open connections; the next request opens a new one. For long-running workers that want to release
   * idle connections.
   */
  public static function closeConnections(): void
  {
    self::$handle = null;
    self::$handlePid = null;
  }

  /**
   * Curl options of a request, without the header callback. For tests; not part of the supported API.
   * @internal
   */
  public static function buildOptions(string $verb, string $url, array $headers, $data = null): array
  {
    $headerLines = self::headerLines($headers);

    $options = [
      CURLOPT_URL => $url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_CONNECTTIMEOUT_MS => self::milliseconds(CopyleaksConfig::GET_CONNECT_TIMEOUT()),
      CURLOPT_TIMEOUT_MS => self::milliseconds(CopyleaksConfig::GET_TIMEOUT()),
      // Accept every compression the local libcurl supports and decompress the response automatically.
      CURLOPT_ENCODING => '',
    ];

    switch ($verb) {
      case 'GET':
        $options[CURLOPT_HTTPGET] = true;
        break;
      case 'POST':
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $data === null ? '' : (is_string($data) ? $data : http_build_query($data));
        break;
      case 'POST-JSON':
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $data === null ? '' : RequestPayload::encode($data);
        break;
      case 'PATCH':
      case 'PUT':
        $options[CURLOPT_CUSTOMREQUEST] = $verb;
        $options[CURLOPT_POSTFIELDS] = $data === null ? '' : RequestPayload::encode($data);
        break;
      case 'MULTIPART':
        if (!is_array($data)) {
          throw new InvalidArgumentException('A multipart request needs an array of form fields.');
        }
        $options[CURLOPT_POST] = true;
        $options[CURLOPT_POSTFIELDS] = $data;
        // curl sets the multipart Content-Type with its boundary.
        $headerLines = array_values(array_filter($headerLines, static function (string $line): bool {
          return stripos($line, 'content-type:') !== 0;
        }));
        break;
      default:
        throw new InvalidArgumentException('Invalid $verb');
    }

    // An empty Expect header stops curl from sending "Expect: 100-continue" and waiting for the server's answer.
    $hasExpect = false;
    foreach ($headerLines as $line) {
      if (stripos($line, 'expect:') === 0) {
        $hasExpect = true;
      }
    }
    if (!$hasExpect) {
      $headerLines[] = 'Expect:';
    }
    $options[CURLOPT_HTTPHEADER] = $headerLines;

    return $options;
  }

  /** 0 stays 0 (no limit); a positive value is at least 1 ms, because 0 means "no limit" to curl. */
  private static function milliseconds($seconds): int
  {
    return $seconds > 0 ? max(1, (int) round($seconds * 1000)) : 0;
  }

  private static function headerLines(array $headers): array
  {
    $lines = [];
    foreach ($headers as $name => $value) {
      $lines[] = is_int($name) ? (string) $value : $name . ': ' . $value;
    }
    return $lines;
  }

  /** @return resource|\CurlHandle */
  private static function handle()
  {
    $pid = function_exists('getmypid') ? getmypid() : null;
    if (self::$handle === null || self::$handlePid !== $pid) {
      $handle = curl_init();
      if ($handle === false) {
        throw new \RuntimeException('Could not initialize curl.');
      }
      self::$handle = $handle;
      self::$handlePid = $pid;
    }
    return self::$handle;
  }
}
