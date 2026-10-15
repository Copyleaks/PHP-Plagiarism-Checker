<?php

namespace Copyleaks;

class SubmissionReferences
{
  /**
   * Detect, parse and validate every reference in the text; the completed webhook then has a referencesValidation
   * summary. null = server default (false).
   */
  public ?bool $validate;

  /** @param bool|null $validate */
  public function __construct(?bool $validate = null)
  {
    $this->validate = $validate;
  }
}
