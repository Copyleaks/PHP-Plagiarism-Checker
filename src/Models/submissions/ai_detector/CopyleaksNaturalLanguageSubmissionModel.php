<?php

namespace Copyleaks;

class CopyleaksNaturalLanguageSubmissionModel extends CopyleaksAiDetectionSubmissionModel
{
    public ?string $language;

    /**
     * AI Logic: explain why the text was detected as AI (the response's explain object).
     * null = server default (false).
     */
    public ?bool $explain;

    /** 1 to 3. null = server default (2). */
    public ?int $sensitivity;

    /**
     * @param string $text
     * @param bool $sandbox
     * @param string|null $language Language code; detected automatically when null.
     * @param bool|null $explain
     * @param int|null $sensitivity
     */
    public function __construct(string $text, bool $sandbox = false, ?string $language = null, ?bool $explain = null, ?int $sensitivity = null)
    {
        parent::__construct($text, $sandbox);
        $this->language = $language;
        $this->explain = $explain;
        $this->sensitivity = $sensitivity;
    }
}
