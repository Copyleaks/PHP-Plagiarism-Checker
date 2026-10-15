<?php

namespace Copyleaks;

class SubmissionScanningExclude
{
  /**
   * Exclude your submissions whose scan ID matches this pattern from batch, internal database and repository
   * results. Wildcards: '*' any number of characters, '.' one non-whitespace character.
   */
  public ?string $idPattern;

  /** string[]: exclude results with backlinks to these domains. Max 10, each 1-256 characters. */
  public ?array $backlinksDomains;

  /** string[]: exclude results that contain any of these phrases. Max 5, each 1-30 characters. */
  public ?array $text;

  /**
   * @param string|null $idPattern
   * @param string[]|null $backlinksDomains
   * @param string[]|null $text
   */
  public function __construct(?string $idPattern = null, ?array $backlinksDomains = null, ?array $text = null)
  {
    $this->idPattern = $idPattern;
    $this->backlinksDomains = $backlinksDomains;
    $this->text = $text;
  }
}
