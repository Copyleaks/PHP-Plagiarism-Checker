<?php

namespace Copyleaks;

use Exception;

/**
 * Too many requests (HTTP 429). getCode() and getStatusCode() return 429.
 * getRetryAfterSeconds() says how long to wait, when the server sent a Retry-After header.
 */
class RateLimitException extends Exception
{
  use HttpResponseDetails;

  /**
   * @param int $code
   * @param Exception|null $previous
   * @param string|null $responseBody
   * @param array<string, string> $responseHeaders
   */
  public function __construct($code = 0, ?Exception $previous = null, ?string $responseBody = null, array $responseHeaders = [])
  {
    parent::__construct("Too many requests. Please wait before calling again.", $code, $previous);
    $this->setResponseDetails($responseBody, $responseHeaders);
  }
}
