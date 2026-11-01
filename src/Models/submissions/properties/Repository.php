<?php

namespace Copyleaks;

class SubmissionRepository
{
  public string $id;

  /** See MaskingPolicy. */
  public ?int $maskingPolicy;

  public function __construct(
    string $id, 
    ?int $maskingPolicy = null)
  {
    $this->id = $id;
    $this->maskingPolicy = $maskingPolicy;
  }
}
