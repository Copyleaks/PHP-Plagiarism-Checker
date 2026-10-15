<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionPatternsModel;

/**
 * AI Logic explanation of the AI text detection.
 */
class CopyleaksAiTextDetectionExplainModel
{
    public ?CopyleaksAiTextDetectionPatternsModel $patterns;

    public function __construct(?CopyleaksAiTextDetectionPatternsModel $patterns = null)
    {
        $this->patterns = $patterns;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['patterns'] ?? null) ? CopyleaksAiTextDetectionPatternsModel::fromArray($data['patterns']) : null
        );
    }
}
