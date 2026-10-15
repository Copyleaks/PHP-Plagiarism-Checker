<?php

namespace Copyleaks;

class ScannedDocument
{
    /** @var string|null */
    public ?string $scanId;

    /** @var int|null */
    public ?int $totalWords;

    /** @var int|null Excluded words. */
    public ?int $totalExcluded;

    /** @var int|null Credits used. */
    public ?int $credits;

    /** @var string|null E.g. ISO 8601. */
    public ?string $creationTime;

    /** @var Metadata|null */
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
