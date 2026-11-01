<?php

namespace Copyleaks;

class CopyleaksAiDetectionSubmissionModel
{
    public string $text;
    public bool $sandbox;

    /**
     * @param string $text
     * @param bool $sandbox Return mock results without using credits.
     */
    public function __construct(string $text, bool $sandbox)
    {
        $this->text = $text;
        $this->sandbox = $sandbox;
    }
}
