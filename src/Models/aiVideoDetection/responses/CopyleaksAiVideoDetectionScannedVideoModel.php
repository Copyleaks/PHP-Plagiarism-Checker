<?php

namespace Copyleaks;

class CopyleaksAiVideoDetectionScannedVideoModel
{
    public ?string $scanId;

    /** Credits used. */
    public ?int $actualCredits;

    public ?int $expectedCredits;

    /** ISO 8601. */
    public ?string $creationTime;

    public function __construct(?string $scanId = null, ?int $actualCredits = null, ?int $expectedCredits = null, ?string $creationTime = null)
    {
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
            is_string($data['scanId'] ?? null) ? $data['scanId'] : null,
            is_numeric($data['actualCredits'] ?? null) ? (int) $data['actualCredits'] : null,
            is_numeric($data['expectedCredits'] ?? null) ? (int) $data['expectedCredits'] : null,
            is_string($data['creationTime'] ?? null) ? $data['creationTime'] : null
        );
    }
}
