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

use Copyleaks\CopyleaksAiTextDetectionResultModel;
use Copyleaks\CopyleaksAiTextDetectionSummaryModel;
use Copyleaks\CopyleaksAiTextDetectionExplainModel;

/**
 * Result of Copyleaks AI text detection.
 * In the completed webhook it is the decoded additionalData of the 'suspected-ai-text' alert,
 * see Alerts::getAIDetectionResult().
 */
class CopyleaksAiTextDetectionResponseModel
{
    /**
     * The version of the AI detection model used for analysis.
     */
    public string $modelVersion;

    /**
     * Segments of the text, each classified as human or AI.
     * @var CopyleaksAiTextDetectionResultModel[]
     */
    public array $results;

    /**
     * Summary of the AI detection analysis.
     */
    public ?CopyleaksAiTextDetectionSummaryModel $summary;

    /**
     * The machine translation provider used when the text was translated to English before detection.
     * 0 means no translation.
     */
    public ?int $translationProvider;

    /**
     * The full translated text.
     * Only present when the text was machine translated to English before detection.
     */
    public ?string $translation;

    /**
     * AI Logic explanation of the detection.
     * Only present when explain (AI Logic) was enabled for the scan.
     */
    public ?CopyleaksAiTextDetectionExplainModel $explain;

    public function __construct(
        string $modelVersion,
        array $results = [],
        ?CopyleaksAiTextDetectionSummaryModel $summary = null,
        ?int $translationProvider = null,
        ?string $translation = null,
        ?CopyleaksAiTextDetectionExplainModel $explain = null
    ) {
        $this->modelVersion = $modelVersion;
        $this->results = $results;
        $this->summary = $summary;
        $this->translationProvider = $translationProvider;
        $this->translation = $translation;
        $this->explain = $explain;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $results = isset($data['results']) && is_array($data['results'])
            ? array_map(fn($item) => CopyleaksAiTextDetectionResultModel::fromArray($item), $data['results'])
            : [];

        return new self(
            $data['modelVersion'] ?? '',
            $results,
            isset($data['summary']) ? CopyleaksAiTextDetectionSummaryModel::fromArray($data['summary']) : null,
            $data['translationProvider'] ?? null,
            $data['translation'] ?? null,
            isset($data['explain']) ? CopyleaksAiTextDetectionExplainModel::fromArray($data['explain']) : null
        );
    }
}
