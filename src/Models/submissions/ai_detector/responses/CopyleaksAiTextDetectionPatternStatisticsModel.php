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
 * Statistics of the AI patterns, one entry per pattern.
 */
class CopyleaksAiTextDetectionPatternStatisticsModel
{
    /**
     * How often each pattern appears in AI-written text.
     * @var float[]
     */
    public array $aiCount;

    /**
     * How often each pattern appears in human-written text.
     * @var float[]
     */
    public array $humanCount;

    /**
     * Ratio between the AI and the human frequency of each pattern.
     * @var float[]
     */
    public array $proportion;

    /**
     * Source of each pattern: 1 = AI, 2 = humanizer.
     * @var int[]
     */
    public array $source;

    public function __construct(
        array $aiCount = [],
        array $humanCount = [],
        array $proportion = [],
        array $source = []
    ) {
        $this->aiCount = $aiCount;
        $this->humanCount = $humanCount;
        $this->proportion = $proportion;
        $this->source = $source;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_array($data['aiCount'] ?? null) ? $data['aiCount'] : [],
            is_array($data['humanCount'] ?? null) ? $data['humanCount'] : [],
            is_array($data['proportion'] ?? null) ? $data['proportion'] : [],
            is_array($data['source'] ?? null) ? $data['source'] : []
        );
    }
}
