<?php

declare(strict_types=1);

namespace Copyleaks;

class NewResultScore
{
    /**
     * @var float The percentage of similar words from all results.
     * The calculation does not include excluded references, quotations, etc.
     */
    public ?float $aggregatedScore;

    public function __construct(?float $aggregatedScore = null)
    {
        $this->aggregatedScore = $aggregatedScore;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['aggregatedScore']) ? (float)$data['aggregatedScore'] : null
        );
    }
}
