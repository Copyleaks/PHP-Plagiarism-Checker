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
 * Summary of the AI text detection analysis.
 * Accepts both camelCase and PascalCase keys (the sandbox sends Human/Ai).
 */
class CopyleaksAiTextDetectionSummaryModel
{
    /**
     * Portion of the text classified as human.
     * Range: 0.0-1.0
     * 0.0 when missing or not a number.
     */
    public float $human;

    /**
     * Portion of the text classified as AI.
     * Range: 0.0-1.0
     * 0.0 when missing or not a number.
     */
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

    /**
     * Returns the camelCase value when it is numeric, otherwise the PascalCase value when it is numeric,
     * otherwise 0.0.
     */
    private static function numberValue(array $data, string $camelKey, string $pascalKey): float
    {
        if (is_numeric($data[$camelKey] ?? null)) {
            return (float) $data[$camelKey];
        }

        return is_numeric($data[$pascalKey] ?? null) ? (float) $data[$pascalKey] : 0.0;
    }
}
