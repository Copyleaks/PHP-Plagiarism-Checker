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
 * The AI Video Detection result that Copyleaks sends to your webhook.
 * Build it from the decoded webhook body: CopyleaksAiVideoDetectionResponseModel::fromArray(json_decode($body, true)).
 * See https://docs.copyleaks.com/reference/data-types/ai-detector/ai-video-detection-response
 */
class CopyleaksAiVideoDetectionResponseModel
{
    /**
     * The version of the AI detection model used for the analysis.
     */
    public ?string $model;

    /**
     * AI-detected ranges of the audio track, in milliseconds.
     */
    public ?CopyleaksAiVideoDetectionTrackResultModel $audioResult;

    /**
     * AI-detected ranges of the visual track, in milliseconds.
     */
    public ?CopyleaksAiVideoDetectionTrackResultModel $visualResult;

    /**
     * Overall AI ratios.
     */
    public ?CopyleaksAiVideoDetectionSummaryModel $summary;

    /**
     * Whether the video was determined to contain AI-generated content.
     */
    public ?bool $isAiDetected;

    /**
     * Information about the analyzed video.
     */
    public ?CopyleaksAiVideoDetectionVideoInfoModel $videoInfo;

    /**
     * Details of the scan.
     */
    public ?CopyleaksAiVideoDetectionScannedVideoModel $scannedVideo;

    public function __construct(
        ?string $model = null,
        ?CopyleaksAiVideoDetectionTrackResultModel $audioResult = null,
        ?CopyleaksAiVideoDetectionTrackResultModel $visualResult = null,
        ?CopyleaksAiVideoDetectionSummaryModel $summary = null,
        ?bool $isAiDetected = null,
        ?CopyleaksAiVideoDetectionVideoInfoModel $videoInfo = null,
        ?CopyleaksAiVideoDetectionScannedVideoModel $scannedVideo = null
    ) {
        $this->model = $model;
        $this->audioResult = $audioResult;
        $this->visualResult = $visualResult;
        $this->summary = $summary;
        $this->isAiDetected = $isAiDetected;
        $this->videoInfo = $videoInfo;
        $this->scannedVideo = $scannedVideo;
    }

    /**
     * Builds the model from the decoded JSON object (an associative array). Unknown keys are ignored,
     * and a value with an unexpected type is read like a missing value.
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['model'] ?? null) ? $data['model'] : null,
            is_array($data['audioResult'] ?? null) ? CopyleaksAiVideoDetectionTrackResultModel::fromArray($data['audioResult']) : null,
            is_array($data['visualResult'] ?? null) ? CopyleaksAiVideoDetectionTrackResultModel::fromArray($data['visualResult']) : null,
            is_array($data['summary'] ?? null) ? CopyleaksAiVideoDetectionSummaryModel::fromArray($data['summary']) : null,
            is_bool($data['isAiDetected'] ?? null) ? $data['isAiDetected'] : null,
            is_array($data['videoInfo'] ?? null) ? CopyleaksAiVideoDetectionVideoInfoModel::fromArray($data['videoInfo']) : null,
            is_array($data['scannedVideo'] ?? null) ? CopyleaksAiVideoDetectionScannedVideoModel::fromArray($data['scannedVideo']) : null
        );
    }
}
