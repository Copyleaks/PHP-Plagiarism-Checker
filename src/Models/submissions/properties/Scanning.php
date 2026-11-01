<?php

namespace Copyleaks;

class SubmissionScanning
{
  /** Compare with online sources. null = server default (true). */
  public ?bool $internet;
  public ?SubmissionScanningExclude $exclude;
  /** SubmissionScanningRepository[]. */
  public ?array $repositories;
  public ?SubmissionScanningCopyleaksDB $copyleaksDb;

  public ?SubmissionScanningInclude $include;

  /**
   * Languages to scan your content against (cross-language detection). Allowed codes:
   * Copyleaks::getAllowedCrossLanguages().
   */
  public ?CrossLanguages $crossLanguages;

  /**
   * @param bool|null $internet
   * @param SubmissionScanningExclude $exclude
   * @param array $repositories
   * @param SubmissionScanningCopyleaksDB $copyleaksDb
   * @param SubmissionScanningInclude $include
   * @param CrossLanguages $crossLanguages
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
