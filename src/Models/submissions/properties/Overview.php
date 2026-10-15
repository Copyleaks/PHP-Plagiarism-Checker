<?php

namespace Copyleaks;

/**
 * Key insights extracted from the scan data. Options left null use the server default (false).
 */
class SubmissionOverview
{
  public ?bool $enable;

  public ?bool $ignoreAIDetection;

  public ?bool $ignorePlagiarismDetection;

  /** Ignore the Grammar Checker results. */
  public ?bool $ignoreWritingFeedback;

  /** Ignore the author's historical data. */
  public ?bool $ignoreAuthorData;

  /**
   * @param bool|null $enable
   * @param bool|null $ignoreAIDetection
   * @param bool|null $ignorePlagiarismDetection
   * @param bool|null $ignoreWritingFeedback
   * @param bool|null $ignoreAuthorData
   */
  public function __construct(
    ?bool $enable = null,
    ?bool $ignoreAIDetection = null,
    ?bool $ignorePlagiarismDetection = null,
    ?bool $ignoreWritingFeedback = null,
    ?bool $ignoreAuthorData = null
  ) {
    $this->enable = $enable;
    $this->ignoreAIDetection = $ignoreAIDetection;
    $this->ignorePlagiarismDetection = $ignorePlagiarismDetection;
    $this->ignoreWritingFeedback = $ignoreWritingFeedback;
    $this->ignoreAuthorData = $ignoreAuthorData;
  }
}
