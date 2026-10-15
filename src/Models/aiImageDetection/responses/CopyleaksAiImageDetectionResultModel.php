<?php

namespace Copyleaks;

/**
 * RLE mask of the AI-detected regions.
 */
class CopyleaksAiImageDetectionResultModel
{
    /**
     * Run start positions in the flattened image array.
     * @var int[]
     */
    public array $starts;

    /**
     * Run lengths, one per start.
     * @var int[]
     */
    public array $lengths;

    /**
     * Confidence band (0-4) of each run; null unless the request used maskType 'heatmap'.
     * @var int[]|null
     */
    public ?array $labels;

    public function __construct(array $starts, array $lengths, ?array $labels = null)
    {
        $this->starts = $starts;
        $this->lengths = $lengths;
        $this->labels = $labels;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            $data['starts'] ?? [],
            $data['lengths'] ?? [],
            is_array($data['labels'] ?? null) ? $data['labels'] : null
        );
    }
}
