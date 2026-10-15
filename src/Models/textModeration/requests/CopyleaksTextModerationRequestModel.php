<?php

namespace Copyleaks;

class CopyleaksTextModerationRequestModel
{

  public string $text;

  /** Return mock results without using credits. */
  public bool $sandbox;

  /** Language code; detected automatically when null. */
  public ?string $language;

  /** CopyleaksTextModerationLabel[] to moderate, 1 to MAX_LABELS. Required. */
  public $labels;

  const MAX_LABELS = 16;

  /**
   * @param string $text
   * @param bool|null $sandbox null is read as false.
   * @param string|null $language
   * @param CopyleaksTextModerationLabel[] $labels
   */
  public function __construct(string $text, ?bool $sandbox = false, ?string $language = null, array $labels = [])
  {
    if (is_null($text)) {
      throw new \InvalidArgumentException('Text cannot be null.');
    }

    if (!is_array($labels) || count($labels) < 1 || count($labels) > self::MAX_LABELS) {
        throw new \InvalidArgumentException('Labels must be an array with at least 1 and no more than ' . self::MAX_LABELS . ' elements.');
    }
    
    if (is_null($sandbox)) {
      $sandbox = false;
    }

    $this->text = $text;
    $this->sandbox = $sandbox;
    $this->language = $language;
    $this->labels = $labels;
  }
}
