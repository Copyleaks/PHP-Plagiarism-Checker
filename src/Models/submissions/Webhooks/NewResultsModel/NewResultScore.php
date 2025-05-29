<?php

declare(strict_types=1);

namespace Copyleaks;

class NewResultScore
{
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
