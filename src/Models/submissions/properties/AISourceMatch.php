<?php

namespace Copyleaks;

class AISourceMatch
{
  /**
   * Find online sources suspected of containing AI-generated text. English documents only.
   * null = server default (false).
   */
  public ?bool $enable;

  /** @param bool $enable */
  public function __construct(
    ?bool $enable = null)
  {
    $this->enable = $enable;
  }
}
