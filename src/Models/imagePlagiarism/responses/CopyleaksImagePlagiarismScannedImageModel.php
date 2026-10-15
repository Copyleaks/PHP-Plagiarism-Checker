<?php

namespace Copyleaks;

/**
 * Details of the image that was checked by the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismScannedImageModel
{
    public ?string $scanId;

    public ?int $expectedCredits;

    /** Credits used. */
    public ?int $actualCredits;

    /** ISO 8601. */
    public ?string $creationTime;

    /** Pixels. */
    public ?int $width;

    /** Pixels. */
    public ?int $height;

    /** The filename you provided. */
    public ?string $filename;

    public function __construct(
        ?string $scanId = null,
        ?int $expectedCredits = null,
        ?int $actualCredits = null,
        ?string $creationTime = null,
        ?int $width = null,
        ?int $height = null,
        ?string $filename = null
    ) {
        $this->scanId = $scanId;
        $this->expectedCredits = $expectedCredits;
        $this->actualCredits = $actualCredits;
        $this->creationTime = $creationTime;
        $this->width = $width;
        $this->height = $height;
        $this->filename = $filename;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['scanId'] ?? null) ? $data['scanId'] : null,
            is_numeric($data['expectedCredits'] ?? null) ? (int) $data['expectedCredits'] : null,
            is_numeric($data['actualCredits'] ?? null) ? (int) $data['actualCredits'] : null,
            is_string($data['creationTime'] ?? null) ? $data['creationTime'] : null,
            is_numeric($data['width'] ?? null) ? (int) $data['width'] : null,
            is_numeric($data['height'] ?? null) ? (int) $data['height'] : null,
            is_string($data['filename'] ?? null) ? $data['filename'] : null
        );
    }
}
