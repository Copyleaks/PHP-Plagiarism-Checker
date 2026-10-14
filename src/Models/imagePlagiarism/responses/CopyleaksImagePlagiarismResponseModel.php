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
 * Result of the Image Plagiarism Detector.
 * Build it from the client's response: CopyleaksImagePlagiarismResponseModel::fromArray(json_decode(json_encode($response), true)).
 * See https://docs.copyleaks.com/reference/data-types/authenticity/results/image-plagiarism-response
 */
class CopyleaksImagePlagiarismResponseModel
{
    /** The developerPayload you sent, or null. */
    public ?string $developerPayload;

    /** Details of the checked image. */
    public ?CopyleaksImagePlagiarismScannedImageModel $scannedImage;

    /** Copies of the image found on the web, and their counts. */
    public ?CopyleaksImagePlagiarismMatchesModel $matches;

    public function __construct(
        ?string $developerPayload = null,
        ?CopyleaksImagePlagiarismScannedImageModel $scannedImage = null,
        ?CopyleaksImagePlagiarismMatchesModel $matches = null
    ) {
        $this->developerPayload = $developerPayload;
        $this->scannedImage = $scannedImage;
        $this->matches = $matches;
    }

    /**
     * Builds the model from the decoded JSON object (an associative array).
     * Unknown keys are ignored, and a value with an unexpected type is read like a missing value.
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['developerPayload'] ?? null) ? $data['developerPayload'] : null,
            is_array($data['scannedImage'] ?? null) ? CopyleaksImagePlagiarismScannedImageModel::fromArray($data['scannedImage']) : null,
            is_array($data['matches'] ?? null) ? CopyleaksImagePlagiarismMatchesModel::fromArray($data['matches']) : null
        );
    }
}
