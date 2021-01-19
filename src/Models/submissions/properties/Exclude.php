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

class SubmissionExclude
{
  /**
   * Exclude quoted text from the scan.
   */
  public bool $quotes;
  /**
   * Exclude referenced text from the scan.
   */
  public bool $references;
  /**
   * Exclude table of contents from the scan.
   */
  public bool $tableOfContents;
  /**
   * Exclude titles from the scan.
   */
  public bool $titles;
  /**
   * When the scanned document is an HTML document, exclude irrelevant text that appears across the site like the website footer or header.
   */
  public bool $htmlTemplate;

  /**
   * @param bool $quotes Exclude quoted text from the scan. 
   * @param bool $references Exclude referenced text from the scan. 
   * @param bool $tableOfContents Exclude table of contents from the scan. 
   * @param bool $titles Exclude titles from the scan. 
   * @param bool $htmlTemplate When the scanned document is an HTML document, exclude irrelevant text that appears across the site like the website footer or header.
   */
  public function __construct(
    bool $quotes = false,
    bool $references = false,
    bool $tableOfContents = false,
    bool $titles = false,
    bool $htmlTemplate = false
  ) {
    $this->quotes = $quotes;
    $this->references = $references;
    $this->tableOfContents = $tableOfContents;
    $this->titles = $titles;
    $this->htmlTemplate = $htmlTemplate;
  }
}
