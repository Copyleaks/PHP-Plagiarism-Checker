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

class SubmissionScanningExclude
{
  /**
   * Exclude your submissions from results if their id matches the supplied pattern. Matched submissions will be excluded from batch, internal database and repositories results.
   * * Supported pattern wildcards:
   *  * '*' Matches any, zero or more, characters.
   *  * '.' Matches a single (non whitespace) character
   */
  public ?string $idPattern;

  /**
   * string[] - Exclude results that contain backlinks to these domains. At most 10 domains, each 1-256 characters.
   */
  public ?array $backlinksDomains;

  /**
   * string[] - Exclude results that contain any of these phrases. At most 5 phrases, each 1-30 characters.
   */
  public ?array $text;

  /**
   * @param string|null $idPattern - Exclude your submissions from results if their id matches the supplied pattern. Matched submissions will be excluded from batch, internal database and repositories results. Supported pattern wildcards: '*' Matches any, zero or more, characters.  '.' Matches a single (non whitespace) character
   * @param string[]|null $backlinksDomains - Exclude results that contain backlinks to these domains (at most 10).
   * @param string[]|null $text - Exclude results that contain any of these phrases (at most 5, each 1-30 characters).
   */
  public function __construct(?string $idPattern = null, ?array $backlinksDomains = null, ?array $text = null)
  {
    $this->idPattern = $idPattern;
    $this->backlinksDomains = $backlinksDomains;
    $this->text = $text;
  }
}
