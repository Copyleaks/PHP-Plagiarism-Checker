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

use Copyleaks\CopyleaksAiTextDetectionPatternStatisticsModel;
use Copyleaks\CopyleaksAiTextDetectionPatternMatchModel;

/**
 * AI patterns found in the text: per-pattern statistics and their positions.
 * The arrays in statistics and in the positions share the same pattern index.
 */
class CopyleaksAiTextDetectionPatternsModel
{
    /**
     * Statistics of each pattern.
     */
    public ?CopyleaksAiTextDetectionPatternStatisticsModel $statistics;

    /**
     * Positions of the patterns in the plain text.
     */
    public ?CopyleaksAiTextDetectionPatternMatchModel $text;

    /**
     * Positions of the patterns in the HTML source.
     * Only present for HTML sources.
     */
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
