<?php
/*
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
*/

namespace Copyleaks;

/**
 * Overall AI ratios of a video, from 0.0 to 1.0. Excluded ranges are not counted.
 */
class CopyleaksAiVideoDetectionSummaryModel
{
    /**
     * AI-detected audio duration divided by the total audible duration.
     */
    public ?float $audioAIRatio;

    /**
     * AI-detected visual duration divided by the total visible duration.
     */
    public ?float $visualAIRatio;

    /**
     * Combined AI ratio of the audio and visual tracks, relative to the total video duration.
     */
    public ?float $overallAIRatio;

    public function __construct(?float $audioAIRatio = null, ?float $visualAIRatio = null, ?float $overallAIRatio = null)
    {
        $this->audioAIRatio = $audioAIRatio;
        $this->visualAIRatio = $visualAIRatio;
        $this->overallAIRatio = $overallAIRatio;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_numeric($data['audioAIRatio'] ?? null) ? (float) $data['audioAIRatio'] : null,
            is_numeric($data['visualAIRatio'] ?? null) ? (float) $data['visualAIRatio'] : null,
            is_numeric($data['overallAIRatio'] ?? null) ? (float) $data['overallAIRatio'] : null
        );
    }
}
