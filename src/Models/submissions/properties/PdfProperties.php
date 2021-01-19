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

class SubmissionPDF
{
  /**
   * Add a request to generate a customizable export of the scan report, in a pdf format.
   * Set to true in order to generate a pdf report for this scan.
   */
  public bool $create;
  /**
   * Customize the title for the PDF report.
   */
  public string $title;
  /**
   * Customize the logo image in the PDF report.
   */
  public string $largeLogo;
  /**
   * When set to true the text in the report will be aligned from right to left.
   */
  public bool $rtl;
  /**
   *@param bool $create Add a request to generate a customizable export of the scan report, in a pdf format. Set to true in order to generate a pdf report for this scan.
   *@param string $title Customize the title for the PDF report.
   *@param string $largeLogo Customize the logo image in the PDF report.
   *@param bool $rtl When set to true the text in the report will be aligned from right to left.
   */
  public function __construct(
    bool $create,
    string $title,
    string $largeLogo,
    bool $rtl
  ) {
    $this->create = $create;
    $this->title = $title;
    $this->largeLogo = $largeLogo;
    $this->rtl = $rtl;
  }
}
