<?php

namespace Copyleaks;

class CopyleaksAiVideoDetectionVideoInfoModel
{
    /** Seconds. */
    public ?float $duration;

    /** null when not available. */
    public ?CopyleaksMediaMetadataModel $metadata;

    public function __construct(?float $duration = null, ?CopyleaksMediaMetadataModel $metadata = null)
    {
        $this->duration = $duration;
        $this->metadata = $metadata;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_numeric($data['duration'] ?? null) ? (float) $data['duration'] : null,
            is_array($data['metadata'] ?? null) ? CopyleaksMediaMetadataModel::fromArray($data['metadata']) : null
        );
    }
}
