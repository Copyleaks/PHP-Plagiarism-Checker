<?php

namespace Copyleaks;

/**
 * Metadata extracted from the image; each field is null when not available.
 */
class CopyleaksImageMetadataModel
{
    /** When the image was created. */
    public ?string $issuedTime;

    /** The AI service or tool that created the image. */
    public ?string $issuedBy;

    public ?string $appOrDeviceUsed;

    /** How the image was generated. */
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
            $data['issuedTime'] ?? null,
            $data['issuedBy'] ?? null,
            $data['appOrDeviceUsed'] ?? null,
            $data['contentSummary'] ?? null
        );
    }
}
