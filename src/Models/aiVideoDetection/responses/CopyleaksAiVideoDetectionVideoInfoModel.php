<?php

namespace Copyleaks;

class CopyleaksAiVideoDetectionVideoInfoModel
{
    /** Seconds. */
    public ?float $duration;

    /** null when not available. */
    public ?CopyleaksVideoMetadataModel $metadata;

    public function __construct(?float $duration = null, ?CopyleaksVideoMetadataModel $metadata = null)
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
            is_array($data['metadata'] ?? null) ? CopyleaksVideoMetadataModel::fromArray($data['metadata']) : null
        );
    }
}
