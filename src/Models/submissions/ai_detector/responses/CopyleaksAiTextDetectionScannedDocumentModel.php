<?php

namespace Copyleaks;

class CopyleaksAiTextDetectionScannedDocumentModel
{
    public ?string $scanId;

    public ?int $totalWords;

    /** Number of excluded words. */
    public ?int $totalExcluded;

    /** Credits used. */
    public ?int $actualCredits;

    public ?int $expectedCredits;

    /** ISO 8601, UTC. */
    public ?string $creationTime;

    public function __construct(
        ?string $scanId = null,
        ?int $totalWords = null,
        ?int $totalExcluded = null,
        ?int $actualCredits = null,
        ?int $expectedCredits = null,
        ?string $creationTime = null
    ) {
        $this->scanId = $scanId;
        $this->totalWords = $totalWords;
        $this->totalExcluded = $totalExcluded;
        $this->actualCredits = $actualCredits;
        $this->expectedCredits = $expectedCredits;
        $this->creationTime = $creationTime;
    }

    /** A value of an unexpected type is read as missing. */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }
        $int = static function ($value): ?int {
            return is_numeric($value) ? (int) $value : null;
        };

        return new self(
            is_scalar($data['scanId'] ?? null) ? (string) $data['scanId'] : null,
            $int($data['totalWords'] ?? null),
            $int($data['totalExcluded'] ?? null),
            $int($data['actualCredits'] ?? null),
            $int($data['expectedCredits'] ?? null),
            is_string($data['creationTime'] ?? null) ? $data['creationTime'] : null
        );
    }
}
