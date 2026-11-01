<?php

namespace Copyleaks;

/**
 * The HTTP response that caused an exception. The status code is also the exception code (getCode()).
 */
trait HttpResponseDetails
{
  /** @var string|null */
  private ?string $responseBody = null;

  /** @var array<string, string> Lowercase names. */
  private array $responseHeaders = [];

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

  private function setResponseDetails(?string $responseBody, array $responseHeaders): void
  {
    $this->responseBody = $responseBody;
    $headers = [];
    foreach ($responseHeaders as $name => $value) {
      $headers[strtolower((string) $name)] = (string) $value;
    }
    $this->responseHeaders = $headers;
  }
}
