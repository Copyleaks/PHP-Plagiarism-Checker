<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

use Exception;

/**
 * The server rejected the request, or no response was received (for example a timeout).
 * getCode() and getStatusCode() return the HTTP status code, or 0 when no response was received.
 */
class CommandException extends Exception
{
  use HttpResponseDetails;

  /**
   * @param string $error The error message.
   * @param int $code The HTTP status code, or 0 when no response was received.
   * @param Exception|null $previous The previous exception.
   * @param string|null $responseBody The raw response body.
   * @param array<string, string> $responseHeaders The response headers.
   */
  public function __construct($error, $code = 0, ?Exception $previous = null, ?string $responseBody = null, array $responseHeaders = [])
  {
    parent::__construct($error, $code, $previous);
    $this->setResponseDetails($responseBody, $responseHeaders);
  }
}
