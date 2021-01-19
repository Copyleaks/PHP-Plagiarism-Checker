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

class SubmissionFilter
{
  /**
   * Enable matching of exact words in the text.
   */
  public bool $identicalEnabled;
  /**
   * Enable matching of nearly identical words with small differences like slow becomes slowly.
   */
  public bool $minorChangesEnabled;
  /**
   * Enable matching of paraphrased content stating similar ideas with different words.
   */
  public bool $relatedMeaningEnabled;
  /**
   * Select results with at least minCopiedWords copied words.
   */
  public ?int $minCopiedWords = null;
  /**
   * Block explicit adult content from the scan results such as web pages containing inappropriate images and videos. SafeSearch is not 100% effective with all websites.
   */
  public bool $safeSearch;
  /**
   * string[] - list of domains to either include or exclude from the scan - depending on the value of domainsMode.
   */
  public array $domains;
  /**
   * SubmissionFilterDomainsMode -
   * Include or Exclude the list of domains you specified under the domains property
   *
   * When Include is selected, Copyleaks will filter out all results that are not part of the properties.filters.domains list.
   *
   * When Exclude is selected, Copyleaks will only find results outside of the properties.filters.domains list.
   */
  public int $domainsMode;

  /**
   *@param bool $identicalEnabled Enable matching of exact words in the text.
   *@param bool $minorChangesEnabled Enable matching of nearly identical words with small differences like slow becomes slowly.
   *@param bool $relatedMeaningEnabled Enable matching of paraphrased content stating similar ideas with different words.
   *@param int $minCopiedWords Select results with at least minCopiedWords copied words.
   *@param bool $safeSearch Block explicit adult content from the scan results such as web pages containing inappropriate images and videos. SafeSearch is not 100% effective with all websites.
   *@param array $domains list of domains to either include or exclude from the scan - depending on the value of domainsMode.
   *@param SubmissionFilterDomainsMode $domainsMode Include or Exclude the list of domains you specified under the domains property
   */
  public function __construct(
    bool $identicalEnabled = true,
    bool $minorChangesEnabled = false,
    bool $relatedMeaningEnabled = false,
    ?int $minCopiedWords = null,
    bool $safeSearch = false,
    array $domains = array(),
    int $domainsMode = SubmissionFilterDomainsMode::Exclude
  ) {
    $this->identicalEnabled = $identicalEnabled;
    $this->minorChangesEnabled = $minorChangesEnabled;
    $this->relatedMeaningEnabled = $relatedMeaningEnabled;
    $this->minCopiedWords = $minCopiedWords;
    $this->safeSearch = $safeSearch;
    $this->domains = $domains;
    $this->domainsMode = $domainsMode;
  }
}
