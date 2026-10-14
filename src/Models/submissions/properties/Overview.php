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
 * Gen-AI Overview settings of a scan (properties.overview): key insights extracted from the scan data.
 * Every option left null uses the server default (false).
 */
class SubmissionOverview
{
  /**
   * Enable the Gen-AI Overview.
   */
  public ?bool $enable;

  /**
   * Ignore AI detection when generating the overview.
   */
  public ?bool $ignoreAIDetection;

  /**
   * Ignore plagiarism detection when generating the overview.
   */
  public ?bool $ignorePlagiarismDetection;

  /**
   * Ignore the Grammar Checker when generating the overview.
   */
  public ?bool $ignoreWritingFeedback;

  /**
   * Ignore the author's historical data when generating the overview.
   */
  public ?bool $ignoreAuthorData;

  /**
   * @param bool|null $enable Enable the Gen-AI Overview.
   * @param bool|null $ignoreAIDetection Ignore AI detection when generating the overview.
   * @param bool|null $ignorePlagiarismDetection Ignore plagiarism detection when generating the overview.
   * @param bool|null $ignoreWritingFeedback Ignore the Grammar Checker when generating the overview.
   * @param bool|null $ignoreAuthorData Ignore the author's historical data when generating the overview.
   */
  public function __construct(
    ?bool $enable = null,
    ?bool $ignoreAIDetection = null,
    ?bool $ignorePlagiarismDetection = null,
    ?bool $ignoreWritingFeedback = null,
    ?bool $ignoreAuthorData = null
  ) {
    $this->enable = $enable;
    $this->ignoreAIDetection = $ignoreAIDetection;
    $this->ignorePlagiarismDetection = $ignorePlagiarismDetection;
    $this->ignoreWritingFeedback = $ignoreWritingFeedback;
    $this->ignoreAuthorData = $ignoreAuthorData;
  }
}
