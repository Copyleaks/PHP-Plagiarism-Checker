<?php

namespace Copyleaks;

/**
 * Match counts of the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismScoreModel
{
    public ?int $totalMatches;

    /** Number of full matches (matchType 0). */
    public ?int $fullMatches;

    /** Number of partial matches (matchType 1). */
    public ?int $partialMatches;

    public function __construct(?int $totalMatches = null, ?int $fullMatches = null, ?int $partialMatches = null)
    {
        $this->totalMatches = $totalMatches;
        $this->fullMatches = $fullMatches;
        $this->partialMatches = $partialMatches;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_numeric($data['totalMatches'] ?? null) ? (int) $data['totalMatches'] : null,
            is_numeric($data['fullMatches'] ?? null) ? (int) $data['fullMatches'] : null,
            is_numeric($data['partialMatches'] ?? null) ? (int) $data['partialMatches'] : null
        );
    }
}
