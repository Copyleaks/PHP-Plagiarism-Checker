<?php

namespace Copyleaks;

class SubmissionAssignment
{
  /** Unique assignment ID. */
  public string $id;

  /** @param string $id */
  public function __construct(string $id)
  {
    $this->id = $id;
  }
}
