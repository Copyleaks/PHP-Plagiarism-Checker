<?php

namespace Copyleaks;

class IdObject
{
  public string $id;
  public function __construct(string $id)
  {
    $this->id = $id;
  }
}
