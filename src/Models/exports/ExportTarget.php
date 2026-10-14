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
 * Where Copyleaks uploads one exported report: an HTTP endpoint, the HTTP verb and optional headers.
 */
class ExportTarget
{
  /**
   * The HTTP url to upload the data.
   */
  public string $endpoint;
  /**
   * The HTTP verb (also called "HTTP Methods") to upload the data to your specified endpoint, for example "POST".
   */
  public string $verb;
  /**
   * List of headers to be submitted with the upload request, such as an "Authorization" header.
   *
   * Example: [["header-key1", "header-value1"], ["header-key2", "header-value2"]]
   */
  public ?array $headers;
  /**
   * @param string $endpoint The HTTP url to upload the data.
   * @param string $verb The HTTP verb to upload the data to your specified endpoint, for example "POST".
   * @param array|null $headers List of headers to be submitted with the upload request. Example: [["header-key1", "header-value1"]]
   */
  public function __construct(
    string $endpoint,
    string $verb,
    ?array $headers = null
  ) {
    $this->endpoint = $endpoint;
    $this->verb = $verb;
    $this->headers = $headers;
  }
}
