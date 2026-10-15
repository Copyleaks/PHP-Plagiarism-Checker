<?php

namespace Copyleaks;

/**
 * PDF report colors. identical, minorChanges, relatedMeaning, aiDetection and writingFeedback (Grammar Checker)
 * are highlight colors.
 */
class ReportCustomizationColors
{
  /** Main strip of the header. */
  public ?string $mainStrip;

  public ?string $titles;
  public ?string $identical;
  public ?string $minorChanges;
  public ?string $relatedMeaning;
  public ?string $aiDetection;
  public ?string $writingFeedback;


  /**
   * @param string $mainStrip
   * @param string $titles
   * @param string $identical
   * @param string $minorChanges
   * @param string $relatedMeaning
   * @param string $aiDetection
   * @param string $writingFeedback
   */
  public function __construct(
    ?string $mainStrip = null, 
    ?string $titles = null, 
    ?string $identical = null, 
    ?string $minorChanges = null, 
    ?string $relatedMeaning = null,
    ?string $aiDetection = null,
    ?string $writingFeedback = null
    )
  {
    $this->mainStrip = $mainStrip;
    $this->titles = $titles;
    $this->identical = $identical;
    $this->minorChanges = $minorChanges;
    $this->relatedMeaning = $relatedMeaning;
    $this->aiDetection = $aiDetection;
    $this->writingFeedback = $writingFeedback;
  }
}
