<?php

namespace Copyleaks;

class Language
{
  /** Language code for cross-language plagiarism detection. */
  public string $code;

  /** @param string $code */
  public function __construct(
    string $code)
  {
    $this->code = $code;
  }
}
