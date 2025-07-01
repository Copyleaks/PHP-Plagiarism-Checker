<?php

namespace Copyleaks;
use Copyleaks\NewResultsInternet;

class Internet extends NewResultsInternet
{
    /**
     * @var Tags[]|null Tags object array. Each element in the array is an instance of the Tags class.
     */
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
