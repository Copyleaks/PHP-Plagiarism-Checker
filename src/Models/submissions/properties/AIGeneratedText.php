<?php

namespace Copyleaks;

class AIGeneratedText
{
  /** Detect whether the text was written by AI. */
  public bool $detect;

  /** 1 to 3. null = server default (2). */
  public ?int $sensitivity;

  /** AI Logic: explain why the text was detected as AI. */
  public ?AIGeneratedTextExplain $explain;

  /**
   * @param bool $detect
   * @param int|null $sensitivity
   * @param AIGeneratedTextExplain|null $explain
   */
  public function __construct(
    bool $detect = false,
    ?int $sensitivity = null,
    ?AIGeneratedTextExplain $explain = null)
  {
    $this->detect = $detect;
    $this->sensitivity = $sensitivity;
    $this->explain = $explain;
  }
}
