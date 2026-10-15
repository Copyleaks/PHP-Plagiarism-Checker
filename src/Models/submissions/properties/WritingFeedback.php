<?php

namespace Copyleaks;

class SubmissionWritingFeedback
{
  /** Check grammar, spelling and more. null = server default (false). */
  public ?bool $enable;

  public ?ScoreWeights $score;

  /**
   * @param bool|null $enable
   * @param ScoreWeights|null $score
   */
  public function __construct(?bool $enable = null, ?ScoreWeights $score = null)
  {
    $this->enable = $enable;
    $this->score = $score;
  }
}
