<?php

namespace Copyleaks;

class SubmissionScanningInclude
{
  /**
   * Include only internal database and repository results whose scan ID matches this pattern.
   * Wildcards: '*' any number of characters, '.' one non-whitespace character.
   */
  public ?string $idPattern;

  /** @param string|null $idPattern */
  public function __construct(?string $idPattern = null)
  {
    $this->idPattern = $idPattern;
  }
}
