<?php

namespace Copyleaks;

class CopyleaksAiImageDetectionScannedDocumentModel
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

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            $data['scanId'] ?? '',
            $data['actualCredits'] ?? 0,
            $data['expectedCredits'] ?? 0,
            $data['creationTime'] ?? ''
        );
    }
}
