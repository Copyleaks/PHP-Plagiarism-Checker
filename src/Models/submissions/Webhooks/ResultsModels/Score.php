<?php

namespace Copyleaks;

class Score
{
    public ?int $identicalWords;
    public ?int $minorChangedWords;
    public ?int $relatedMeaningWords;
    public ?float $aggregatedScore;

    public function __construct(
        ?int $identicalWords = null,
        ?int $minorChangedWords = null,
        ?int $relatedMeaningWords = null,
        ?float $aggregatedScore = null
    ) {
        $this->identicalWords = $identicalWords;
        $this->minorChangedWords = $minorChangedWords;
        $this->relatedMeaningWords = $relatedMeaningWords;
        $this->aggregatedScore = $aggregatedScore;
    }

    public static function fromArray($data): self
    {
        // 🔒 Validate input type
        if (!is_array($data)) {
            // Convert to default Score object
            return new self();
        }

        return new self(
            $data['identicalWords'] ?? null,
            $data['minorChangedWords'] ?? null,
            $data['relatedMeaningWords'] ?? null,
            $data['aggregatedScore'] ?? null
        );
    }
}
