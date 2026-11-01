<?php

namespace Copyleaks;

/**
 * The AI Video Detection webhook body: fromArray(json_decode($body, true)).
 * Check $error first: when the scan failed, it is set and the result properties are null.
 * See https://docs.copyleaks.com/reference/data-types/ai-detector/ai-video-detection-response
 */
class CopyleaksAiVideoDetectionResponseModel
{
    /** Version of the model used. */
    public ?string $model;

    /** AI ranges of the audio track. */
    public ?CopyleaksAiVideoDetectionTrackResultModel $audioResult;

    /** AI ranges of the visual track. */
    public ?CopyleaksAiVideoDetectionTrackResultModel $visualResult;

    public ?CopyleaksAiVideoDetectionSummaryModel $summary;

    public ?bool $isAiDetected;

    public ?CopyleaksAiVideoDetectionVideoInfoModel $videoInfo;

    public ?CopyleaksScannedDocumentModel $scannedVideo;

    /** Why the scan failed. Set only when it failed. */
    public ?Error $error;

    /** From the submit request. */
    public ?string $developerPayload;

    public function __construct(
        ?string $model = null,
        ?CopyleaksAiVideoDetectionTrackResultModel $audioResult = null,
        ?CopyleaksAiVideoDetectionTrackResultModel $visualResult = null,
        ?CopyleaksAiVideoDetectionSummaryModel $summary = null,
        ?bool $isAiDetected = null,
        ?CopyleaksAiVideoDetectionVideoInfoModel $videoInfo = null,
        ?CopyleaksScannedDocumentModel $scannedVideo = null,
        ?Error $error = null,
        ?string $developerPayload = null
    ) {
        $this->model = $model;
        $this->audioResult = $audioResult;
        $this->visualResult = $visualResult;
        $this->summary = $summary;
        $this->isAiDetected = $isAiDetected;
        $this->videoInfo = $videoInfo;
        $this->scannedVideo = $scannedVideo;
        $this->error = $error;
        $this->developerPayload = $developerPayload;
    }

    /** Unknown keys are ignored; a value of an unexpected type is read as missing. */
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
            is_array($data['scannedVideo'] ?? null) ? CopyleaksScannedDocumentModel::fromArray($data['scannedVideo']) : null,
            is_array($data['error'] ?? null) ? Error::fromArray($data['error']) : null,
            is_string($data['developerPayload'] ?? null) ? $data['developerPayload'] : null
        );
    }
}
