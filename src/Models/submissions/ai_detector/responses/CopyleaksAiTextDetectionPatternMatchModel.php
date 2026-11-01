<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionPositionsModel;

/**
 * Character and word positions of the AI patterns.
 */
class CopyleaksAiTextDetectionPatternMatchModel
{
    /** May be missing. */
    public ?CopyleaksAiTextDetectionPositionsModel $chars;

    public ?CopyleaksAiTextDetectionPositionsModel $words;

    public function __construct(
        ?CopyleaksAiTextDetectionPositionsModel $chars = null,
        ?CopyleaksAiTextDetectionPositionsModel $words = null
    ) {
        $this->chars = $chars;
        $this->words = $words;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['chars'] ?? null) ? CopyleaksAiTextDetectionPositionsModel::fromArray($data['chars']) : null,
            is_array($data['words'] ?? null) ? CopyleaksAiTextDetectionPositionsModel::fromArray($data['words']) : null
        );
    }
}
