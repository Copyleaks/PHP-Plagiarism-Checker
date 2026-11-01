<?php

namespace Copyleaks;

class SubmissionFilter
{
  /** Match exact words. */
  public bool $identicalEnabled;
  /** Match nearly identical words, e.g. slow and slowly. null = server default (true). */
  public ?bool $minorChangesEnabled;
  /** Match paraphrased content. null = server default (true). */
  public ?bool $relatedMeaningEnabled;
  /** Keep only results with at least this many copied words. */
  public ?int $minCopiedWords = null;
  /** Block explicit adult content from the results. Not 100% effective on all websites. */
  public bool $safeSearch;
  /** string[] of domains to include or exclude, see $domainsMode. */
  public array $domains;
  /** See SubmissionFilterDomainsMode. */
  public int $domainsMode;

  /** Allow results from the domain of the submitted URL. */
  public bool $allowSameDomain;

  /**
   * @param bool $identicalEnabled
   * @param bool|null $minorChangesEnabled
   * @param bool|null $relatedMeaningEnabled
   * @param int $minCopiedWords
   * @param bool $safeSearch
   * @param array $domains
   * @param SubmissionFilterDomainsMode $domainsMode
   * @param bool $allowSameDomain
   */
  public function __construct(
    bool $identicalEnabled = true,
    ?bool $minorChangesEnabled = null,
    ?bool $relatedMeaningEnabled = null,
    ?int $minCopiedWords = null,
    bool $safeSearch = false,
    array $domains = array(),
    int $domainsMode = SubmissionFilterDomainsMode::Exclude,
    bool $allowSameDomain = false
  ) {
    $this->identicalEnabled = $identicalEnabled;
    $this->minorChangesEnabled = $minorChangesEnabled;
    $this->relatedMeaningEnabled = $relatedMeaningEnabled;
    $this->minCopiedWords = $minCopiedWords;
    $this->safeSearch = $safeSearch;
    $this->domains = $domains;
    $this->domainsMode = $domainsMode;
    $this->allowSameDomain = $allowSameDomain;
    }
}
