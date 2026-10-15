<?php

namespace Copyleaks;

/**
 * Positions in the scanned text, as parallel arrays of start offsets and lengths.
 * fromArray() also accepts PascalCase Starts/Lengths, which the sandbox sends.
 */
class CopyleaksAiTextDetectionPositionsModel
{
    /** @var int[] */
    public array $starts;

    /**
     * One per start.
     * @var int[]
     */
    public array $lengths;

    /**
     * HTML group IDs, one per start; HTML positions only.
     * @var int[]|null
     */
    public ?array $groupIds;

    public function __construct(array $starts = [], array $lengths = [], ?array $groupIds = null)
    {
        $this->starts = $starts;
        $this->lengths = $lengths;
        $this->groupIds = $groupIds;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            self::listValue($data, 'starts', 'Starts') ?? [],
            self::listValue($data, 'lengths', 'Lengths') ?? [],
            is_array($data['groupIds'] ?? null) ? $data['groupIds'] : null
        );
    }

    private static function listValue(array $data, string $camelKey, string $pascalKey): ?array
    {
        if (is_array($data[$camelKey] ?? null)) {
            return $data[$camelKey];
        }

        return is_array($data[$pascalKey] ?? null) ? $data[$pascalKey] : null;
    }
}
