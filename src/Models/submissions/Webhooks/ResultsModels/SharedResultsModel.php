<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class SharedResultsModel
{
    public ?string $id;
    public ?string $title;
    public ?string $introduction;
    public ?int $matchedWords;
    public ?string $scanId;
    public ?Metadata $metadata;

    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $introduction = null,
        ?int $matchedWords = null,
        ?string $scanId = null,
        ?Metadata $metadata = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->introduction = $introduction;
        $this->matchedWords = $matchedWords;
        $this->scanId = $scanId;
        $this->metadata = $metadata;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? null,
            $data['introduction'] ?? null,
            $data['matchedWords'] ?? null,
            $data['scanId'] ?? null,
            isset($data['metadata']) ? Metadata::fromArray($data['metadata']) : null
        );
    }
}
