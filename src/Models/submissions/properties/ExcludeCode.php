<?php

namespace Copyleaks;

class ExcludeCode
{
  public bool $comments;

  public function __construct(
    bool $comments = false)
  {
    $this->comments = $comments;
  }
}
