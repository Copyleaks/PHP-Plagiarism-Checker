<?php

namespace Copyleaks;

/**
 * Finds sources in the Copyleaks internal AI database that contain AI-generated text.
 */
class InternalAISourceMatch
{
  /** null = server default (false). */
  public ?bool $enable;

  /** @param bool|null $enable */
  public function __construct(?bool $enable = null)
  {
    $this->enable = $enable;
  }
}
