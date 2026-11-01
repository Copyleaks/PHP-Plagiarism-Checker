<?php

namespace Copyleaks;

class SubmissionInstitution
{
  /** Unique institution ID. */
  public string $id;

  /** @param string $id */
  public function __construct(string $id)
  {
    $this->id = $id;
  }
}
