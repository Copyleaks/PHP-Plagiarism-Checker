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
        ?float $aggregatedScore

    ) {
        $this->identicalWords = $identicalWords;
        $this->minorChangedWords = $minorChangedWords;
        $this->relatedMeaningWords = $relatedMeaningWords;
        $this->aggregatedScore=$aggregatedScore;
    }

}
