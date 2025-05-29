<?php

namespace Copyleaks;

class ScannedDocument
{
    public ?string $scanId;
    public ?string $creationTime;
    public ?int $totalWords;
    public ?int $totalExcluded;
    public ?int $credits;
    public ?Metadata $metadata;

    public function __construct(
        ?string $scanId = null,
        ?string $creationTime = null,
        ?int $totalWords = null,
        ?int $totalExcluded = null,
        ?int $credits = null,
        ?Metadata $metadata = null
    ) {
        $this->scanId = $scanId;
        $this->creationTime = $creationTime;
        $this->totalWords = $totalWords;
        $this->totalExcluded = $totalExcluded;
        $this->credits = $credits;
        $this->metadata = $metadata;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['scanId'] ?? null,
            $data['creationTime'] ?? null,
            isset($data['totalWords']) ? (int)$data['totalWords'] : null,
            isset($data['totalExcluded']) ? (int)$data['totalExcluded'] : null,
            isset($data['credits']) ? (int)$data['credits'] : null,
            isset($data['metadata']) && is_array($data['metadata']) ? Metadata::fromArray($data['metadata']) : null
        );
    }
}
