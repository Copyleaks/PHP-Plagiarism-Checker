<?php

namespace Copyleaks;

/**
 * AI detection result of one video track (audio or visual). Positions and lengths are in milliseconds.
 */
class CopyleaksAiVideoDetectionTrackResultModel extends CopyleaksAiVideoDetectionRangesModel
{
    /** Ranges that were not scored and are left out of the AI ratios. */
    public ?CopyleaksAiVideoDetectionRangesModel $exclude;

    public function __construct(array $starts = [], array $lengths = [], ?CopyleaksAiVideoDetectionRangesModel $exclude = null)
    {
        parent::__construct($starts, $lengths);
        $this->exclude = $exclude;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['starts'] ?? null) ? $data['starts'] : [],
            is_array($data['lengths'] ?? null) ? $data['lengths'] : [],
            is_array($data['exclude'] ?? null) ? CopyleaksAiVideoDetectionRangesModel::fromArray($data['exclude']) : null
        );
    }
}
