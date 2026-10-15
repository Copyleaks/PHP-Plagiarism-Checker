<?php

namespace Copyleaks;

/**
 * Overall AI ratios of a video, from 0.0 to 1.0. Excluded ranges are not counted.
 */
class CopyleaksAiVideoDetectionSummaryModel
{
    /** AI audio duration / total audible duration. */
    public ?float $audioAIRatio;

    /** AI visual duration / total visible duration. */
    public ?float $visualAIRatio;

    /** Combined audio and visual AI ratio, relative to the total video duration. */
    public ?float $overallAIRatio;

    public function __construct(?float $audioAIRatio = null, ?float $visualAIRatio = null, ?float $overallAIRatio = null)
    {
        $this->audioAIRatio = $audioAIRatio;
        $this->visualAIRatio = $visualAIRatio;
        $this->overallAIRatio = $overallAIRatio;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_numeric($data['audioAIRatio'] ?? null) ? (float) $data['audioAIRatio'] : null,
            is_numeric($data['visualAIRatio'] ?? null) ? (float) $data['visualAIRatio'] : null,
            is_numeric($data['overallAIRatio'] ?? null) ? (float) $data['overallAIRatio'] : null
        );
    }
}
