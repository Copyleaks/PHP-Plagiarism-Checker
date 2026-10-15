<?php

namespace Copyleaks;

class CopyleaksStartRequestModel
{
  /** IDs of the scans submitted with the check-credits action to run as full scans. */
  public array $trigger;
  /** See CopyleaksStartErrorHandlings. */
  public int $errorHandling;
  /**
   * @param string[] $trigger
   * @param CopyleaksStartErrorHandlings $errorHandling
   */
  public function __construct(array $trigger, int $errorHandling = CopyleaksStartErrorHandlings::IGNORE)
  {
    $this->trigger = $trigger;
    $this->errorHandling = $errorHandling;
  }
}

class CopyleaksStartErrorHandlings
{
  /** Cancel the request if any of the scans has an error. */
  const CANCEL = 0;
  /** Start the scans even if some of them have errors. */
  const IGNORE = 1;
}
