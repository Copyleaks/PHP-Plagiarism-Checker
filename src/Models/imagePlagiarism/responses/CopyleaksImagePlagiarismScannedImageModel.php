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
 * Details of the image that was checked by the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismScannedImageModel
{
    /** The scan ID you provided. */
    public ?string $scanId;

    /** The credits the scan was expected to consume. */
    public ?int $expectedCredits;

    /** The credits consumed by the scan. */
    public ?int $actualCredits;

    /** When the scan was created (ISO 8601). */
    public ?string $creationTime;

    /** Image width, in pixels. */
    public ?int $width;

    /** Image height, in pixels. */
    public ?int $height;

    /** The filename you provided. */
    public ?string $filename;

    public function __construct(
        ?string $scanId = null,
        ?int $expectedCredits = null,
        ?int $actualCredits = null,
        ?string $creationTime = null,
        ?int $width = null,
        ?int $height = null,
        ?string $filename = null
    ) {
        $this->scanId = $scanId;
        $this->expectedCredits = $expectedCredits;
        $this->actualCredits = $actualCredits;
        $this->creationTime = $creationTime;
        $this->width = $width;
        $this->height = $height;
        $this->filename = $filename;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['scanId'] ?? null) ? $data['scanId'] : null,
            is_numeric($data['expectedCredits'] ?? null) ? (int) $data['expectedCredits'] : null,
            is_numeric($data['actualCredits'] ?? null) ? (int) $data['actualCredits'] : null,
            is_string($data['creationTime'] ?? null) ? $data['creationTime'] : null,
            is_numeric($data['width'] ?? null) ? (int) $data['width'] : null,
            is_numeric($data['height'] ?? null) ? (int) $data['height'] : null,
            is_string($data['filename'] ?? null) ? $data['filename'] : null
        );
    }
}
