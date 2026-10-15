<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiImageDetectionResultModel;
use Copyleaks\CopyleaksAiImageDetectionSummaryModel;
use Copyleaks\CopyleaksAiImageDetectionImageInfoModel;
use Copyleaks\CopyleaksAiImageDetectionScannedDocumentModel;

/**
 * AI image detection result. For an asynchronous scan, check $error first: when the scan failed, it is set and
 * the result properties are null.
 */
class CopyleaksAiImageDetectionResponseModel
{
    /** Version of the model used. */
    public string $model;

    /** RLE mask of the AI regions. */
    public ?CopyleaksAiImageDetectionResultModel $result;

    public ?CopyleaksAiImageDetectionSummaryModel $summary;

    public ?CopyleaksAiImageDetectionImageInfoModel $imageInfo;

    public ?CopyleaksAiImageDetectionScannedDocumentModel $scannedDocument;

    /** null when missing. */
    public ?bool $isAiDetected;

    /**
     * One entry per object found; null unless the request used objectDetection. Objects not found are left out,
     * so look entries up by $object.
     * @var CopyleaksAiImageDetectedObjectModel[]|null
     */
    public ?array $detectedObjects;

    /** Why the scan failed. Set only in the webhook of a failed asynchronous scan. */
    public ?Error $error;

    /** From the asynchronous submit request. */
    public ?string $developerPayload;

    public function __construct(
        string $model,
        ?CopyleaksAiImageDetectionResultModel $result = null,
        ?CopyleaksAiImageDetectionSummaryModel $summary = null,
        ?CopyleaksAiImageDetectionImageInfoModel $imageInfo = null,
        ?CopyleaksAiImageDetectionScannedDocumentModel $scannedDocument = null,
        ?bool $isAiDetected = null,
        ?array $detectedObjects = null,
        ?Error $error = null,
        ?string $developerPayload = null
    ) {
        $this->model = $model;
        $this->result = $result;
        $this->summary = $summary;
        $this->imageInfo = $imageInfo;
        $this->scannedDocument = $scannedDocument;
        $this->isAiDetected = $isAiDetected;
        $this->detectedObjects = $detectedObjects;
        $this->error = $error;
        $this->developerPayload = $developerPayload;
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
            $detectedObjects,
            is_array($data['error'] ?? null) ? Error::fromArray($data['error']) : null,
            is_string($data['developerPayload'] ?? null) ? $data['developerPayload'] : null
        );
    }
}
