<?php

namespace Copyleaks;

/**
 * Weight of each correction category in the overall writing score, 0.0 to 1.0. null = server default (1.0).
 */
class ScoreWeights
{
    public ?float $grammarScoreWeight;
    public ?float $mechanicsScoreWeight;
    public ?float $sentenceStructureScoreWeight;
    public ?float $wordChoiceScoreWeight;

    /**
     * @param float|null $grammarScoreWeight
     * @param float|null $mechanicsScoreWeight
     * @param float|null $sentenceStructureScoreWeight
     * @param float|null $wordChoiceScoreWeight
     */
    public function __construct(?float $grammarScoreWeight = null, ?float $mechanicsScoreWeight = null, ?float $sentenceStructureScoreWeight = null, ?float $wordChoiceScoreWeight = null)
    {
        $this->grammarScoreWeight = $grammarScoreWeight;
        $this->mechanicsScoreWeight = $mechanicsScoreWeight;
        $this->sentenceStructureScoreWeight = $sentenceStructureScoreWeight;
        $this->wordChoiceScoreWeight = $wordChoiceScoreWeight;
    }
}
