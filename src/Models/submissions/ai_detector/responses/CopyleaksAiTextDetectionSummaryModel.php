<?php

namespace Copyleaks;

/**
 * fromArray() also accepts PascalCase Human/Ai, which the sandbox sends.
 */
class CopyleaksAiTextDetectionSummaryModel
{
    /** Share of the text classified as human, 0.0-1.0; 0.0 when missing or not a number. */
    public float $human;

    /** Share of the text classified as AI, 0.0-1.0; 0.0 when missing or not a number. */
    public float $ai;

    public function __construct(float $human, float $ai)
    {
        $this->human = $human;
        $this->ai = $ai;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            self::numberValue($data, 'human', 'Human'),
            self::numberValue($data, 'ai', 'Ai')
        );
    }

    private static function numberValue(array $data, string $camelKey, string $pascalKey): float
    {
        if (is_numeric($data[$camelKey] ?? null)) {
            return (float) $data[$camelKey];
        }

        return is_numeric($data[$pascalKey] ?? null) ? (float) $data[$pascalKey] : 0.0;
    }
}
