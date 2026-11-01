<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionMatchModel;

/**
 * A segment of the text classified as human or AI.
 */
class CopyleaksAiTextDetectionResultModel
{
    /** 1 = human, 2 = AI; 0 when missing or not a number. */
    public int $classification;

    /** Deprecated by the server and may be removed. null when missing or not a number. */
    public ?float $probability;

    /**
     * Positions of the segment. Items that are not JSON objects are skipped.
     * @var CopyleaksAiTextDetectionMatchModel[]
     */
    public array $matches;

    public function __construct(int $classification, ?float $probability = null, array $matches = [])
    {
        $this->classification = $classification;
        $this->probability = $probability;
        $this->matches = $matches;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $matches = [];
        if (is_array($data['matches'] ?? null)) {
            foreach ($data['matches'] as $item) {
                if (is_array($item)) {
                    $matches[] = CopyleaksAiTextDetectionMatchModel::fromArray($item);
                }
            }
        }

        $classification = $data['classification'] ?? null;
        $probability = $data['probability'] ?? null;

        return new self(
            is_numeric($classification) ? (int) $classification : 0,
            is_numeric($probability) ? (float) $probability : null,
            $matches
        );
    }
}
