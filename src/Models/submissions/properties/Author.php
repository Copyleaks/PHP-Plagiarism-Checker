<?php

namespace Copyleaks;

class SubmissionAuthor
{
  /** Unique author ID. Use the same ID for the same author, so Copyleaks can learn their writing patterns. */
  public string $id;
  /** @param string $id */
  public function __construct(string $id)
  {
    $this->id = $id;
  }
}
