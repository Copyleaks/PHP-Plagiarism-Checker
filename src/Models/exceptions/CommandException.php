<?php

namespace Copyleaks;

use Exception;

/**
 * The server rejected the request, or no response was received (e.g. a timeout).
 * getCode() and getStatusCode() return the HTTP status code, or 0 when there was no response.
 */
class CommandException extends Exception
{
  use HttpResponseDetails;

  /**
   * @param string $error
   * @param int $code
   * @param Exception|null $previous
   * @param string|null $responseBody
   * @param array<string, string> $responseHeaders
   */
  public function __construct($error, $code = 0, ?Exception $previous = null, ?string $responseBody = null, array $responseHeaders = [])
  {
    parent::__construct($error, $code, $previous);
    $this->setResponseDetails($responseBody, $responseHeaders);
  }
}
