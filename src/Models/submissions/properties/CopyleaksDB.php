<?php

namespace Copyleaks;

class SubmissionScanningCopyleaksDB
{
  /** Also compare against content YOU uploaded to the internal database. True also indexes this scan there. */
  public bool $includeMySubmissions;
  /** Also compare against content OTHERS uploaded to the internal database. True also indexes this scan there. */
  public bool $includeOthersSubmissions;
  /**
   * @param bool $includeMySubmissions
   * @param bool $includeOthersSubmissions
   */
  public function __construct(bool $includeMySubmissions, bool $includeOthersSubmissions)
  {
    $this->includeMySubmissions = $includeMySubmissions;
    $this->includeOthersSubmissions = $includeOthersSubmissions;
  }
}
