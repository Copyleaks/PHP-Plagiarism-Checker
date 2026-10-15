<?php

namespace Copyleaks;

/**
 * Statistics of the AI patterns, one entry per pattern.
 */
class CopyleaksAiTextDetectionPatternStatisticsModel
{
    /**
     * How often each pattern appears in AI-written text.
     * @var float[]
     */
    public array $aiCount;

    /**
     * How often each pattern appears in human-written text.
     * @var float[]
     */
    public array $humanCount;

    /**
     * AI to human frequency ratio of each pattern.
     * @var float[]
     */
    public array $proportion;

    /**
     * 1 = AI, 2 = humanizer.
     * @var int[]
     */
    public array $source;

    public function __construct(
        array $aiCount = [],
        array $humanCount = [],
        array $proportion = [],
        array $source = []
    ) {
        $this->aiCount = $aiCount;
        $this->humanCount = $humanCount;
        $this->proportion = $proportion;
        $this->source = $source;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['aiCount'] ?? null) ? $data['aiCount'] : [],
            is_array($data['humanCount'] ?? null) ? $data['humanCount'] : [],
            is_array($data['proportion'] ?? null) ? $data['proportion'] : [],
            is_array($data['source'] ?? null) ? $data['source'] : []
        );
    }
}
