<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionMatchTextModel;

/**
 * Positions of a classified segment in the scanned text.
 */
class CopyleaksAiTextDetectionMatchModel
{
    /** Positions in the plain text. */
    public ?CopyleaksAiTextDetectionMatchTextModel $text;

    /** Positions in the HTML source; HTML sources only. */
    public ?CopyleaksAiTextDetectionMatchTextModel $html;

    public function __construct(
        ?CopyleaksAiTextDetectionMatchTextModel $text = null,
        ?CopyleaksAiTextDetectionMatchTextModel $html = null
    ) {
        $this->text = $text;
        $this->html = $html;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['text'] ?? null) ? CopyleaksAiTextDetectionMatchTextModel::fromArray($data['text']) : null,
            is_array($data['html'] ?? null) ? CopyleaksAiTextDetectionMatchTextModel::fromArray($data['html']) : null
        );
    }
}
