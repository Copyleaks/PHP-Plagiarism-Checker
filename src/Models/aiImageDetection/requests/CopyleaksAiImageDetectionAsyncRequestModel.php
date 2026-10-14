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
 * Request model for asynchronous AI Image Detection of an image URL.
 * The result is sent to the webhook when processing is complete.
 * See https://docs.copyleaks.com/reference/actions/ai-image-detector/submit
 */
class CopyleaksAiImageDetectionAsyncRequestModel
{
    /**
     * Publicly accessible URL of the image to analyze.
     */
    public string $url;

    /**
     * The name of the image file including its extension.
     * Supported extensions: .jpg, .jpeg, .png, .webp, .tiff, .bmp, .heic, .heif
     */
    public string $fileName;

    /**
     * The AI image detection model, for example CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA.
     */
    public string $model;

    /**
     * Where to send the result.
     */
    public CopyleaksAsyncWebhooks $webhooks;

    /**
     * Use sandbox mode to test your integration without consuming credits. Returns mock results.
     * Leave null to get the server default (false).
     */
    public ?bool $sandbox;

    /**
     * A string that Copyleaks includes in the webhook, to match the result with your own records.
     */
    public ?string $developerPayload;

    /**
     * The type of detection mask in the result: 'binary' (default) or 'heatmap'. See CopyleaksAiImageMaskTypes.
     * Leave null to get the server default ('binary').
     */
    public ?string $maskType;

    /**
     * Objects to locate and segment within the image, at most 3 (CopyleaksAiImageObjectDetectionItem[]).
     * The result then has a detectedObjects array. Leave null to skip object detection.
     * @var CopyleaksAiImageObjectDetectionItem[]|null
     */
    public ?array $objectDetection;

    /**
     * Headers to add when Copyleaks downloads the image from $url, as [["Header-Name", "Header-Value"], ...].
     * @var string[][]|null
     */
    public ?array $headers;

    /**
     * @param string $url Publicly accessible URL of the image to analyze.
     * @param string $fileName The name of the image file including its extension.
     * @param string $model The AI image detection model, for example CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA.
     * @param CopyleaksAsyncWebhooks $webhooks Where to send the result.
     * @param bool|null $sandbox Use sandbox mode (free, mock results). null = server default (false).
     * @param string|null $developerPayload A string that Copyleaks includes in the webhook.
     * @param string|null $maskType 'binary' or 'heatmap'. null = server default ('binary').
     * @param CopyleaksAiImageObjectDetectionItem[]|null $objectDetection Objects to locate in the image (at most 3).
     * @param string[][]|null $headers Headers to add when Copyleaks downloads the image.
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
