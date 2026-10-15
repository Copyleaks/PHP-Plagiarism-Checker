<?php

namespace Copyleaks;

class SubmissionCourse
{
  /** Unique course ID. */
  public string $id;

  /** @param string $id */
  public function __construct(string $id)
  {
    $this->id = $id;
  }
}
