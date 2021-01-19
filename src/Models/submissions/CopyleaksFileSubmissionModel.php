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

class CopyleaksFileSubmissionModel extends CopyleaksSubmissionModel
{
  /**
   * A base64 data string of a file. If you would like to scan plain text, encode it as base64 and submit it.
   */
  public string $base64;
  /**
   * The name of the file as it will appear in the Copyleaks scan report Make sure to include the right extension for your filetype.
   */
  public string $filename;
  /**
   * Check inner properties for more details.
   */
  public SubmissionProperties $properties;
  /**
   * @param string $base64 A base64 data string of a file. If you would like to scan plain text, encode it as base64 and submit it.
   * @param string $filename The name of the file as it will appear in the Copyleaks scan report Make sure to include the right extension for your filetype.
   * @param SubmissionProperties $properties Check inner properties for more details.
   */
  public function __construct(
    string $base64,
    string $filename,
    SubmissionProperties $properties
  ) {
    parent::__construct($properties);
    $this->base64 = $base64;
    $this->filename = $filename;
  }
}
