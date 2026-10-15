<?php

namespace Copyleaks;

class AIGeneratedTextExplain
{
  /** null = server default (false). Explanations are returned only for the languages AI Logic supports. */
  public ?bool $enable;

  /** @param bool|null $enable */
  public function __construct(?bool $enable = null)
  {
    $this->enable = $enable;
  }
}
