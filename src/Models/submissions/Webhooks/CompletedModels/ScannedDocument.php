<?php

namespace Copyleaks;

class ScannedDocument
{
    /**
     * @var string|null The unique scan ID provided by you.
     */
    public ?string $scanId;

    /**
     * @var int|null Total number of words found in the scanned text.
     */
    public ?int $totalWords;

    /**
     * @var int|null Number of excluded words in the submitted content.
     */
    public ?int $totalExcluded;

    /**
     * @var int|null Overall credits used for the scan.
     */
    public ?int $credits;

    /**
     * @var string|null The creation time of the scan (e.g., in ISO 8601 format).
     */
    public ?string $creationTime;

    /**
     * @var Metadata|null Metadata object.
     */
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
