<?php

namespace Copyleaks;

class Score
{
    /**
     * @var int|null Number of words which matched exactly.
     */
    public ?int $identicalWords;

    /**
     * @var int|null Number of nearly identical words with small differences like 'slow' and 'slowly'.
     */
    public ?int $minorChangedWords;

    /**
     * @var int|null Number of paraphrased words showing similar ideas with different words.
     */
    public ?int $relatedMeaningWords;

    /**
     * @var float|null The percentage of similar words from all results.
     * The calculation does not include excluded references, quotations, etc.
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
