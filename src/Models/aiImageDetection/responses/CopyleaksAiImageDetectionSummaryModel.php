<?php

namespace Copyleaks;

class CopyleaksAiImageDetectionSummaryModel
{
    /** Share of pixels suspected to be human-created, 0.0-1.0. */
    public float $human;

    /** Share of pixels suspected to be AI-generated, 0.0-1.0. */
    public float $ai;

    public function __construct(float $human, float $ai)
    {
        $this->human = $human;
        $this->ai = $ai;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            $data['human'] ?? 0.0,
            $data['ai'] ?? 0.0
        );
    }
}
