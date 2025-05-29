<?php

namespace Copyleaks;
use Copyleaks\NewResultsInternet;

class Internet extends NewResultsInternet
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
