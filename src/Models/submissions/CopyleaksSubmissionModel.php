<?php

namespace Copyleaks;

class CopyleaksSubmissionModel
{
  public SubmissionProperties $properties;
  /** @param SubmissionProperties $properties */
  public function __construct(SubmissionProperties $properties)
  {
    $this->properties = $properties;
  }
}
