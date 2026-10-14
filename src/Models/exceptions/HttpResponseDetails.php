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

/**
 * Details of the HTTP response that caused an exception.
 * The HTTP status code is also the exception code (getCode()).
 */
trait HttpResponseDetails
{
  /**
   * @var string|null The raw response body, or null when there was none.
   */
  private ?string $responseBody = null;

  /**
   * @var array<string, string> The response headers, with lowercase names.
   */
  private array $responseHeaders = [];

  /**
   * @return int The HTTP status code of the response, or 0 when no response was received
   * (for example a timeout or a connection error).
   */
  public function getStatusCode(): int
  {
    return (int) $this->getCode();
  }

  /**
   * @return string|null The raw response body, or null when there was none.
   */
  public function getResponseBody(): ?string
  {
    return $this->responseBody;
  }

  /**
   * @return array<string, string> The response headers. Names are lowercase.
   */
  public function getResponseHeaders(): array
  {
    return $this->responseHeaders;
  }

  /**
   * @param string $name The header name, in any case.
   * @return string|null The value of the response header, or null when it is missing.
   */
  public function getResponseHeader(string $name): ?string
  {
    return $this->responseHeaders[strtolower($name)] ?? null;
  }

  /**
   * @return int|null How many seconds to wait before calling again, read from the Retry-After header
   * (a number of seconds or an HTTP date). null when the response has no valid Retry-After header.
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
