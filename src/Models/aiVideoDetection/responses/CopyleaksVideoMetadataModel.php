<?php

namespace Copyleaks;

/**
 * Metadata extracted from a video file, e.g. C2PA provenance data.
 */
class CopyleaksVideoMetadataModel
{
    /** When the video was created. */
    public ?string $issuedTime;

    /** The AI service or tool that created the video. */
    public ?string $issuedBy;

    public ?string $appOrDeviceUsed;

    /** How the video was generated. */
    public ?string $contentSummary;

    public function __construct(
        ?string $issuedTime = null,
        ?string $issuedBy = null,
        ?string $appOrDeviceUsed = null,
        ?string $contentSummary = null
    ) {
        $this->issuedTime = $issuedTime;
        $this->issuedBy = $issuedBy;
        $this->appOrDeviceUsed = $appOrDeviceUsed;
        $this->contentSummary = $contentSummary;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['issuedTime'] ?? null) ? $data['issuedTime'] : null,
            is_string($data['issuedBy'] ?? null) ? $data['issuedBy'] : null,
            is_string($data['appOrDeviceUsed'] ?? null) ? $data['appOrDeviceUsed'] : null,
            is_string($data['contentSummary'] ?? null) ? $data['contentSummary'] : null
        );
    }
}
