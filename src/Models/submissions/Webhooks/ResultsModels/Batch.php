<?php

namespace Copyleaks;

use Copyleaks\SharedResultsModel;

class Batch extends SharedResultsModel
{
    public ?array $tags;

    public function __construct(?array $tags = [])
    {
        $this->tags = $tags;
    }

    public static function fromArray(array $data): self
    {
        $tags = $data['tags'] ?? [];
        return new self($tags);
    }
}
