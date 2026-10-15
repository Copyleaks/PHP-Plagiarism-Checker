<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionPatternStatisticsModel;
use Copyleaks\CopyleaksAiTextDetectionPatternMatchModel;

/**
 * AI patterns found in the text. The arrays in statistics and in the positions share the same pattern index.
 */
class CopyleaksAiTextDetectionPatternsModel
{
    public ?CopyleaksAiTextDetectionPatternStatisticsModel $statistics;

    /** Positions in the plain text. */
    public ?CopyleaksAiTextDetectionPatternMatchModel $text;

    /** Positions in the HTML source; HTML sources only. */
    public ?CopyleaksAiTextDetectionPatternMatchModel $html;

    public function __construct(
        ?CopyleaksAiTextDetectionPatternStatisticsModel $statistics = null,
        ?CopyleaksAiTextDetectionPatternMatchModel $text = null,
        ?CopyleaksAiTextDetectionPatternMatchModel $html = null
    ) {
        $this->statistics = $statistics;
        $this->text = $text;
        $this->html = $html;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['statistics'] ?? null) ? CopyleaksAiTextDetectionPatternStatisticsModel::fromArray($data['statistics']) : null,
            is_array($data['text'] ?? null) ? CopyleaksAiTextDetectionPatternMatchModel::fromArray($data['text']) : null,
            is_array($data['html'] ?? null) ? CopyleaksAiTextDetectionPatternMatchModel::fromArray($data['html']) : null
        );
    }
}
