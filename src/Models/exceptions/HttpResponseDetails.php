<?php

namespace Copyleaks;

/**
 * The HTTP response that caused an exception. The status code is also the exception code (getCode()).
 * The getError*() methods read the API's JSON error body, {"error": {"type", "id", "code", "message", "url",
 * "details"}}, see https://docs.copyleaks.com/using-the-apis/api-errors/ . Each returns null (getErrorDetails(): an
 * empty array) when the body is not JSON, has no "error" object, or lacks that field.
 */
trait HttpResponseDetails
{
  /** @var string|null */
  private ?string $responseBody = null;

  /** @var array<string, string> Lowercase names. */
  private array $responseHeaders = [];

  /** @var array|null The parsed error body, read on first use. */
  private ?array $errorBody = null;

  /**
   * @return int 0 when no response was received (e.g. a timeout or a connection error).
   */
  public function getStatusCode(): int
  {
    return (int) $this->getCode();
  }

  /** @return string|null */
  public function getResponseBody(): ?string
  {
    return $this->responseBody;
  }

  /** @return array<string, string> Lowercase names. */
  public function getResponseHeaders(): array
  {
    return $this->responseHeaders;
  }

  /**
   * @param string $name Case-insensitive.
   * @return string|null null when missing.
   */
  public function getResponseHeader(string $name): ?string
  {
    return $this->responseHeaders[strtolower($name)] ?? null;
  }

  /**
   * @return int|null Seconds to wait before calling again, from the Retry-After header (seconds or an HTTP date).
   * null when there is no valid Retry-After header.
   */
  public function getRetryAfterSeconds(): ?int
  {
    $value = $this->getResponseHeader('retry-after');
    if ($value === null) {
      return null;
    }
    $value = trim($value);
    if (ctype_digit($value)) {
      return (int) $value;
    }
    $time = strtotime($value);
    return $time === false ? null : max(0, $time - time());
  }

  /**
   * @return string|null error.type, e.g. CopyleaksErrorTypes::INVALID_REQUEST_ERROR. Other values are returned as is.
   */
  public function getErrorType(): ?string
  {
    return $this->errorBody()['type'];
  }

  /** @return string|null error.id, e.g. "missing_parameter". */
  public function getErrorId(): ?string
  {
    return $this->errorBody()['id'];
  }

  /** @return int|null error.code, the number in the API docs' error table. */
  public function getErrorCode(): ?int
  {
    return $this->errorBody()['code'];
  }

  /** @return string|null error.message. getMessage() is unchanged. */
  public function getErrorMessage(): ?string
  {
    return $this->errorBody()['message'];
  }

  /** @return string|null error.url, the documentation of this error. */
  public function getErrorUrl(): ?string
  {
    return $this->errorBody()['url'];
  }

  /** @return ErrorDetail[] error.details, the parameters that caused the error. Empty when there are none. */
  public function getErrorDetails(): array
  {
    return $this->errorBody()['details'];
  }

  private function setResponseDetails(?string $responseBody, array $responseHeaders): void
  {
    $this->responseBody = $responseBody;
    $this->errorBody = null;
    $headers = [];
    foreach ($responseHeaders as $name => $value) {
      $headers[strtolower((string) $name)] = (string) $value;
    }
    $this->responseHeaders = $headers;
  }

  /** Never throws: anything unexpected in the body gives null fields. */
  private function errorBody(): array
  {
    if ($this->errorBody !== null) {
      return $this->errorBody;
    }
    $parsed = ['type' => null, 'id' => null, 'code' => null, 'message' => null, 'url' => null, 'details' => []];
    $body = $this->responseBody === null || $this->responseBody === '' ? null : json_decode($this->responseBody);
    $error = $body instanceof \stdClass ? ($body->error ?? null) : null;
    if ($error instanceof \stdClass) {
      foreach (['type', 'id', 'message', 'url'] as $name) {
        $value = $error->{$name} ?? null;
        $parsed[$name] = is_string($value) ? $value : null;
      }
      $code = $error->code ?? null;
      if (is_int($code)) {
        $parsed['code'] = $code;
      } elseif (is_string($code) && preg_match('/^([+-]?)([0-9]+)$/D', trim($code, " \t\n\r\x0B\f"), $match) === 1) {
        // Surrounding whitespace is ignored. FILTER_VALIDATE_INT rejects leading zeros and numbers out of the int range.
        $number = filter_var($match[1] . (ltrim($match[2], '0') ?: '0'), FILTER_VALIDATE_INT);
        $parsed['code'] = $number === false ? null : $number;
      }
      $details = $error->details ?? null;
      if (is_array($details)) {
        foreach ($details as $detail) {
          if ($detail instanceof \stdClass) {
            $param = $detail->param ?? null;
            $message = $detail->message ?? null;
            $parsed['details'][] = new ErrorDetail(is_string($param) ? $param : null, is_string($message) ? $message : null);
          }
        }
      }
    }
    return $this->errorBody = $parsed;
  }
}
