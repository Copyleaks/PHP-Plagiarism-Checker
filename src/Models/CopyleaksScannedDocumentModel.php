<?php

namespace Copyleaks;

/**
 * Scan ID, credits and creation time of an AI Image, AI Video or Image Plagiarism scan.
 */
class CopyleaksScannedDocumentModel
{
    public string $scanId;

    /** Credits used. */
    public int $actualCredits;

    public int $expectedCredits;

    /** ISO 8601. */
    public string $creationTime;

    public function __construct(
        string $scanId,
        int $actualCredits,
        int $expectedCredits,
        string $creationTime
    ) {
        $this->scanId = $scanId;
        $this->actualCredits = $actualCredits;
        $this->expectedCredits = $expectedCredits;
        $this->creationTime = $creationTime;
    }

    /**
     * A missing value, or one of an unexpected type, is read as '' or 0.
     * @return static|null
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new static(
            is_scalar($data['scanId'] ?? null) ? (string) $data['scanId'] : '',
            is_numeric($data['actualCredits'] ?? null) ? (int) $data['actualCredits'] : 0,
            is_numeric($data['expectedCredits'] ?? null) ? (int) $data['expectedCredits'] : 0,
            is_scalar($data['creationTime'] ?? null) ? (string) $data['creationTime'] : ''
        );
    }
}
