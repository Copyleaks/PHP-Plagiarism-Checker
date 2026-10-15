<?php

namespace Copyleaks;

/**
 * Time ranges of a video track, in milliseconds.
 */
class CopyleaksAiVideoDetectionRangesModel
{
    /** @var int[] */
    public array $starts;

    /**
     * One per start.
     * @var int[]
     */
    public array $lengths;

    public function __construct(array $starts = [], array $lengths = [])
    {
        $this->starts = $starts;
        $this->lengths = $lengths;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['starts'] ?? null) ? $data['starts'] : [],
            is_array($data['lengths'] ?? null) ? $data['lengths'] : []
        );
    }
}
