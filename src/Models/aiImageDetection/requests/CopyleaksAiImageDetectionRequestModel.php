<?php

namespace Copyleaks;

class CopyleaksAiImageDetectionRequestModel
{
    /**
     * The image, base64-encoded. 512x512 to 6000x4500 px (27 megapixels), under 32 MB;
     * PNG, JPEG, BMP, WebP or HEIC/HEIF.
     * @var string
     */
    public $base64;

    /**
     * File name with extension (.png, .bmp, .jpg, .jpeg, .webp, .heic, .heif), max 255 chars.
     * @var string
     */
    public $fileName;

    /**
     * Full model name or alias, e.g. "ai-image-1-ultra-01-09-2025" or "ai-image-1-ultra".
     * See CopyleaksAiImageDetectionModels.
     * @var string
     */
    public $model;

    /**
     * Return mock results without using credits. For development only. Default false.
     * @var bool
     */
    public $sandbox;

    /**
     * 'binary', 'heatmap' or 'none', see CopyleaksAiImageMaskTypes. null = server default ('binary').
     * @var string|null
     */
    public $maskType;

    /**
     * Up to 3 objects to locate, e.g. new CopyleaksAiImageObjectDetectionItem('face'). The response then has one
     * detectedObjects entry per object found. null = no object detection.
     * @var CopyleaksAiImageObjectDetectionItem[]|null
     */
    public $objectDetection;

    /**
     * @param string $base64
     * @param string $fileName
     * @param string $model
     * @param bool $sandbox
     * @param string|null $maskType
     * @param CopyleaksAiImageObjectDetectionItem[]|null $objectDetection
     */
    public function __construct($base64, $fileName, $model, $sandbox = false, ?string $maskType = null, ?array $objectDetection = null)
    {
        $this->base64 = $base64;
        $this->fileName = $fileName;
        $this->model = $model;
        $this->sandbox = $sandbox;
        $this->maskType = $maskType;
        $this->objectDetection = $objectDetection;
    }

    /**
     * The four original keys are always present, even when null, as in earlier releases. The newer options are
     * added as in the request body: only when set.
     * @return array
     */
    public function toArray()
    {
        return [
            'base64' => $this->base64,
            'fileName' => $this->fileName,
            'model' => $this->model,
            'sandbox' => $this->sandbox
        ] + (array) RequestPayload::toPayload(['maskType' => $this->maskType, 'objectDetection' => $this->objectDetection]);
    }

    /** @return string */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /** @return array Validation errors; empty if valid. */
    public function validate()
    {
        $errors = [];

        if (empty($this->base64)) {
            $errors[] = 'Base64 field is required';
        }

        if (empty($this->fileName)) {
            $errors[] = 'FileName field is required';
        }

        if (empty($this->model)) {
            $errors[] = 'Model field is required';
        }

        if (!empty($this->fileName)) {
            $allowedExtensions = ['.png', '.bmp', '.jpg', '.jpeg', '.webp', '.heic', '.heif'];
            $extension = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
            if (!in_array('.' . $extension, $allowedExtensions)) {
                $errors[] = 'Unsupported file extension. Allowed: ' . implode(', ', $allowedExtensions);
            }
        }

        return $errors;
    }
}
?>