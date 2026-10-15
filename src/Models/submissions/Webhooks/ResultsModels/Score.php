<?php

namespace Copyleaks;

class Score
{
    /** @var int|null Words that matched exactly. */
    public ?int $identicalWords;

    /** @var int|null Nearly identical words, e.g. 'slow' and 'slowly'. */
    public ?int $minorChangedWords;

    /** @var int|null Paraphrased words. */
    public ?int $relatedMeaningWords;

    /**
     * @var float|null Percentage of similar words across all results, not counting excluded references, quotes, etc.
     */
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

        return new self(
            $data['identicalWords'] ?? null,
            $data['minorChangedWords'] ?? null,
            $data['relatedMeaningWords'] ?? null,
            $data['aggregatedScore'] ?? null
        );
    }
}
