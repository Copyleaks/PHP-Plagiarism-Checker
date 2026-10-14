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
 * Details of the AI Text Detection scan (the response's scannedDocument).
 */
class CopyleaksAiTextDetectionScannedDocumentModel
{
    /**
     * The scan ID you provided.
     */
    public ?string $scanId;

    /**
     * Total number of words in the scanned text.
     */
    public ?int $totalWords;

    /**
     * Number of excluded words.
     */
    public ?int $totalExcluded;

    /**
     * The credits consumed by the scan.
     */
    public ?int $actualCredits;

    /**
     * The credits the scan was expected to consume.
     */
    public ?int $expectedCredits;

    /**
     * When the scan was created (ISO 8601, UTC).
     */
    public ?string $creationTime;

    public function __construct(
        ?string $scanId = null,
        ?int $totalWords = null,
        ?int $totalExcluded = null,
        ?int $actualCredits = null,
        ?int $expectedCredits = null,
        ?string $creationTime = null
    ) {
        $this->scanId = $scanId;
        $this->totalWords = $totalWords;
        $this->totalExcluded = $totalExcluded;
        $this->actualCredits = $actualCredits;
        $this->expectedCredits = $expectedCredits;
        $this->creationTime = $creationTime;
    }

    /**
     * Builds the model from a decoded JSON object. A value with an unexpected type is read like a missing value.
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }
        $int = static function ($value): ?int {
            return is_numeric($value) ? (int) $value : null;
        };

        return new self(
            is_scalar($data['scanId'] ?? null) ? (string) $data['scanId'] : null,
            $int($data['totalWords'] ?? null),
            $int($data['totalExcluded'] ?? null),
            $int($data['actualCredits'] ?? null),
            $int($data['expectedCredits'] ?? null),
            is_string($data['creationTime'] ?? null) ? $data['creationTime'] : null
        );
    }
}
