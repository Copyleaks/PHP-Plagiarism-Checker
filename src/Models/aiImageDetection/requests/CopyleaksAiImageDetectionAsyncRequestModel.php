<?php

namespace Copyleaks;

/**
 * Asynchronous AI Image Detection of an image URL; the result is sent to the webhook.
 * See https://docs.copyleaks.com/reference/actions/ai-image-detector/submit
 */
class CopyleaksAiImageDetectionAsyncRequestModel
{
    /** Publicly accessible image URL. */
    public string $url;

    /** File name with extension (.jpg, .jpeg, .png, .webp, .tiff, .bmp, .heic, .heif). */
    public string $fileName;

    /** E.g. CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA. */
    public string $model;

    /** Where to send the result. */
    public CopyleaksAsyncWebhooks $webhooks;

    /** Return mock results without using credits. null = server default (false). */
    public ?bool $sandbox;

    /** Returned in the webhook, to match the result with your own records. */
    public ?string $developerPayload;

    /** 'binary' or 'heatmap', see CopyleaksAiImageMaskTypes. null = server default ('binary'). */
    public ?string $maskType;

    /**
     * Up to 3 objects to locate; the result then has a detectedObjects array. null = no object detection.
     * @var CopyleaksAiImageObjectDetectionItem[]|null
     */
    public ?array $objectDetection;

    /**
     * Headers for downloading the image from $url, as [["Header-Name", "Header-Value"], ...].
     * @var string[][]|null
     */
    public ?array $headers;

    /**
     * @param string $url
     * @param string $fileName
     * @param string $model
     * @param CopyleaksAsyncWebhooks $webhooks
     * @param bool|null $sandbox
     * @param string|null $developerPayload
     * @param string|null $maskType
     * @param CopyleaksAiImageObjectDetectionItem[]|null $objectDetection
     * @param string[][]|null $headers
     */
    public function __construct(
        string $url,
        string $fileName,
        string $model,
        CopyleaksAsyncWebhooks $webhooks,
        ?bool $sandbox = null,
        ?string $developerPayload = null,
        ?string $maskType = null,
        ?array $objectDetection = null,
        ?array $headers = null
    ) {
        $this->url = $url;
        $this->fileName = $fileName;
        $this->model = $model;
        $this->webhooks = $webhooks;
        $this->sandbox = $sandbox;
        $this->developerPayload = $developerPayload;
        $this->maskType = $maskType;
        $this->objectDetection = $objectDetection;
        $this->headers = $headers;
    }
}
