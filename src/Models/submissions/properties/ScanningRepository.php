<?php

namespace Copyleaks;

class SubmissionScanningRepository extends SubmissionRepository
{
  /** Compare against MY submissions in the repository. */
  public bool $includeMySubmissions;
  /** Compare against OTHER users' submissions in the repository. */
  public bool $includeOthersSubmissions;
  /**
   * @param bool $includeMySubmissions
   * @param bool $includeOthersSubmissions
   */
  public function __construct(string $id, bool $includeMySubmissions, bool $includeOthersSubmissions)
  {
    parent::__construct($id);
    $this->includeMySubmissions = $includeMySubmissions;
    $this->includeOthersSubmissions = $includeOthersSubmissions;
  }
}
