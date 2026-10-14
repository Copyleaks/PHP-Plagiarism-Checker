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

class SubmissionScanning
{
  /**
   * Compare your content with online sources.
   */
  public ?bool $internet;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionScanningExclude $exclude;
  /**
   * SubmissionScanningRepository[] - Check inner properties of SubmissionScanningRepository for more details.
   */
  public ?array $repositories;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionScanningCopyleaksDB $copyleaksDb;

  /**
   * Only include results whose scan ID matches a pattern. Check inner properties for more details.
   */
  public ?SubmissionScanningInclude $include;

  /**
   * Cross-language plagiarism detection: the languages to scan your content against.
   * Get the allowed language codes with Copyleaks::getAllowedCrossLanguages().
   */
  public ?CrossLanguages $crossLanguages;

  /**
   *@param bool|null $internet Compare your content with online sources. Leave null to get the server default (true).
   *@param SubmissionScanningExclude $exclude Check inner properties of SubmissionScanningExclude for more details.
   *@param array $repositories SubmissionScanningRepository[] - Check inner properties of SubmissionScanningRepository for more details.
   *@param SubmissionScanningCopyleaksDB $copyleaksDb Check inner properties for more details.
   *@param SubmissionScanningInclude $include Only include results whose scan ID matches a pattern.
   *@param CrossLanguages $crossLanguages The languages to scan your content against (cross-language plagiarism detection).
   */
  public function __construct(
    ?bool $internet = null,
    ?SubmissionScanningExclude $exclude = null,
    ?array $repositories = null,
    ?SubmissionScanningCopyleaksDB $copyleaksDb = null,
    ?SubmissionScanningInclude $include = null,
    ?CrossLanguages $crossLanguages = null
  ) {
    $this->internet = $internet;
    $this->exclude = $exclude;
    $this->repositories = $repositories;
    $this->copyleaksDb = $copyleaksDb;
    $this->include = $include;
    $this->crossLanguages = $crossLanguages;
  }
}
