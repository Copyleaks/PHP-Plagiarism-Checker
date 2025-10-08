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
 * Metadata about the AI image detection scan operation.
 */
class CopyleaksAiImageDetectionScannedDocumentModel
{
    /**
     * The unique identifier for this scan.
     */
    public string $scanId;

    /**
     * The actual number of credits consumed by this scan.
     */
    public int $actualCredits;

    /**
     * The expected number of credits for this scan.
     */
    public int $expectedCredits;

    /**
     * ISO 8601 timestamp of when the scan was created.
     */
    public string $creationTime;

    public function __construct(
        string $scanId,
        int $actualCredits,
        int $expectedCredits,
        string $creationTime
    ) {
        $this->scanId = $scanId;
        $this->actualCredits = $actualCredits;
        $this->expectedCredits = $expectedCredits;
        $this->creationTime = $creationTime;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            $data['scanId'] ?? '',
            $data['actualCredits'] ?? 0,
            $data['expectedCredits'] ?? 0,
            $data['creationTime'] ?? ''
        );
    }
}
