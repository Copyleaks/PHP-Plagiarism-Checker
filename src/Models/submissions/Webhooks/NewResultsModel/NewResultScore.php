<?php

declare(strict_types=1); // Optional: Enforces stricter type checking

namespace Copyleaks;

/**
 * Represents a result score, specifically holding an aggregated score value.
 * Properties are public, nullable, and initialized via the constructor.
 */
class NewResultScore
{
    /**
     * The aggregated score value.
     * Using float as the PHP equivalent for Java's Double.
     * @var float|null
     */
    public ?float $aggregatedScore;

    /**
     * Constructor to initialize the aggregated score.
     * The parameter is optional and defaults to null.
     *
     * @param float|null $aggregatedScore The aggregated score value.
     */
    public function __construct(?float $aggregatedScore = null)
    {
        $this->aggregatedScore = $aggregatedScore;
    }

}