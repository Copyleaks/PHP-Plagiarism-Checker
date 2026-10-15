<?php

namespace Copyleaks;

class CopyleaksWritingAssistantSubmissionModel
{
    public string $text;
    public ?bool $sandbox;
    public ?string $language;
    public ?ScoreWeights $score;

    /**
     * @param string $text 1 to 25000 characters.
     * @param bool|null $sandbox Return mock results without using credits.
     * @param string|null $language Language code; detected automatically when null.
     * @param ScoreWeights|null $score
     */
    public function __construct(string $text, ?bool $sandbox = null, ?string $language = null, ?ScoreWeights $score = null)
    {
        $this->text = $text;
        $this->sandbox = $sandbox;
        $this->language = $language;
        $this->score = $score;
    }
}
