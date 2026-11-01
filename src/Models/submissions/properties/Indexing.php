<?php

namespace Copyleaks;

class SubmissionIndexing
{
  /** SubmissionRepository[] to add the document to. */
  public ?array $repositories;

  /** Add the document to the Copyleaks Shared Data Hub. null = server default (false). */
  public ?bool $copyleaksDb;

  /**
   * @param SubmissionRepository[]|null $repositories
   * @param bool|null $copyleaksDb
   */
  public function __construct(?array $repositories = null, ?bool $copyleaksDb = null)
  {
    $this->repositories = $repositories;
    $this->copyleaksDb = $copyleaksDb;
  }
}
