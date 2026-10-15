<?php

namespace Copyleaks;

/**
 * Metadata extracted from an image or video file, e.g. C2PA provenance data; each field is null when not available.
 */
class CopyleaksMediaMetadataModel
{
    /** When the file was created. */
    public ?string $issuedTime;

    /** The AI service or tool that created the file. */
    public ?string $issuedBy;

    public ?string $appOrDeviceUsed;

    /** How the file was generated. */
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

    /**
     * A value that is not a string or other scalar is read as missing.
     * @return static|null
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }
        $string = static function ($value): ?string {
            return is_scalar($value) ? (string) $value : null;
        };

        return new static(
            $string($data['issuedTime'] ?? null),
            $string($data['issuedBy'] ?? null),
            $string($data['appOrDeviceUsed'] ?? null),
            $string($data['contentSummary'] ?? null)
        );
    }
}
