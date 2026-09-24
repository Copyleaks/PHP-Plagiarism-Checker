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

use Copyleaks\CopyleaksAiTextDetectionMatchModel;

/**
 * A segment of the text classified as human or AI.
 */
class CopyleaksAiTextDetectionResultModel
{
    /**
     * Classification of the segment: 1 = human, 2 = AI.
     */
    public int $classification;

    /**
     * Probability of the classification.
     * Deprecated by the server and may be removed; null when missing.
     */
    public ?float $probability;

    /**
     * Positions of the segment in the scanned text.
     * @var CopyleaksAiTextDetectionMatchModel[]
     */
    public array $matches;

    public function __construct(int $classification, ?float $probability = null, array $matches = [])
    {
        $this->classification = $classification;
        $this->probability = $probability;
        $this->matches = $matches;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $matches = isset($data['matches']) && is_array($data['matches'])
            ? array_map(fn($item) => CopyleaksAiTextDetectionMatchModel::fromArray($item), $data['matches'])
            : [];

        return new self(
            $data['classification'] ?? 0,
            $data['probability'] ?? null,
            $matches
        );
    }
}
