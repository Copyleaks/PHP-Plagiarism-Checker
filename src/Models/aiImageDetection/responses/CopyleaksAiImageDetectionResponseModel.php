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

use Copyleaks\CopyleaksAiImageDetectionResultModel;
use Copyleaks\CopyleaksAiImageDetectionSummaryModel;
use Copyleaks\CopyleaksAiImageDetectionImageInfoModel;
use Copyleaks\CopyleaksAiImageDetectionScannedDocumentModel;

/**
 * Response model for Copyleaks AI image detection analysis.
 * Contains the AI detection results, image information, and scan metadata.
 */
class CopyleaksAiImageDetectionResponseModel
{
    /**
     * The version of the AI detection model used for analysis.
     */
    public string $model;

    /**
     * RLE-encoded mask data containing arrays of start positions and lengths for AI-detected regions.
     */
    public ?CopyleaksAiImageDetectionResultModel $result;

    /**
     * Summary statistics of the AI detection analysis.
     */
    public ?CopyleaksAiImageDetectionSummaryModel $summary;

    /**
     * Information about the analyzed image.
     */
    public ?CopyleaksAiImageDetectionImageInfoModel $imageInfo;

    /**
     * Metadata about the scan operation.
     */
    public ?CopyleaksAiImageDetectionScannedDocumentModel $scannedDocument;

    /**
     * Whether the image was determined to contain AI-generated content. null when missing.
     */
    public ?bool $isAiDetected;

    /**
     * The objects found in the image, one entry per object.
     * Only present when the request used objectDetection; null otherwise.
     * Objects that were requested but not found are left out, so look entries up by $object.
     * @var CopyleaksAiImageDetectedObjectModel[]|null
     */
    public ?array $detectedObjects;

    public function __construct(
        string $model,
        ?CopyleaksAiImageDetectionResultModel $result = null,
        ?CopyleaksAiImageDetectionSummaryModel $summary = null,
        ?CopyleaksAiImageDetectionImageInfoModel $imageInfo = null,
        ?CopyleaksAiImageDetectionScannedDocumentModel $scannedDocument = null,
        ?bool $isAiDetected = null,
        ?array $detectedObjects = null
    ) {
        $this->model = $model;
        $this->result = $result;
        $this->summary = $summary;
        $this->imageInfo = $imageInfo;
        $this->scannedDocument = $scannedDocument;
        $this->isAiDetected = $isAiDetected;
        $this->detectedObjects = $detectedObjects;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $detectedObjects = null;
        if (is_array($data['detectedObjects'] ?? null)) {
            $detectedObjects = [];
            foreach ($data['detectedObjects'] as $item) {
                if (is_array($item)) {
                    $detectedObjects[] = CopyleaksAiImageDetectedObjectModel::fromArray($item);
                }
            }
        }

        return new self(
            $data['model'] ?? '',
            isset($data['result']) ? CopyleaksAiImageDetectionResultModel::fromArray($data['result']) : null,
            isset($data['summary']) ? CopyleaksAiImageDetectionSummaryModel::fromArray($data['summary']) : null,
            isset($data['imageInfo']) ? CopyleaksAiImageDetectionImageInfoModel::fromArray($data['imageInfo']) : null,
            isset($data['scannedDocument']) ? CopyleaksAiImageDetectionScannedDocumentModel::fromArray($data['scannedDocument']) : null,
            is_bool($data['isAiDetected'] ?? null) ? $data['isAiDetected'] : null,
            $detectedObjects
        );
    }
}
