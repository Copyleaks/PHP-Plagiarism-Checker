<?php

namespace Copyleaks;

use Exception;

/**
 * Copyleaks servers are unavailable (HTTP 503). getCode() and getStatusCode() return 503.
 * getRetryAfterSeconds() says how long to wait, when the server sent a Retry-After header.
 */
class UnderMaintenanceException extends Exception
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
    parent::__construct("Copyleaks is Under Maintenance, please visit https://status.copyleaks.com", $code, $previous);
    $this->setResponseDetails($responseBody, $responseHeaders);
  }
}
