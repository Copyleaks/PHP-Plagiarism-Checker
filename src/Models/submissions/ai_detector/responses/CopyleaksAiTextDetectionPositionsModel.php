<?php
/********************************************************************************
 The MIT License(MIT)
 
 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)
 
 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:
 
 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.
 
 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
********************************************************************************/

namespace Copyleaks;

/**
 * Positions in the scanned text, as parallel arrays of start offsets and lengths.
 * Accepts both camelCase and PascalCase keys for starts/lengths (the sandbox sends Starts/Lengths).
 */
class CopyleaksAiTextDetectionPositionsModel
{
    /**
     * Start offsets of the matched segments.
     * @var int[]
     */
    public array $starts;

    /**
     * Lengths of the matched segments, one per start offset.
     * @var int[]
     */
    public array $lengths;

    /**
     * HTML group ids, one per start offset.
     * Only present for HTML positions.
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

    /**
     * Returns the camelCase value when it is an array, otherwise the PascalCase value when it is an array,
     * otherwise null.
     */
    private static function listValue(array $data, string $camelKey, string $pascalKey): ?array
    {
        if (is_array($data[$camelKey] ?? null)) {
            return $data[$camelKey];
        }

        return is_array($data[$pascalKey] ?? null) ? $data[$pascalKey] : null;
    }
}
