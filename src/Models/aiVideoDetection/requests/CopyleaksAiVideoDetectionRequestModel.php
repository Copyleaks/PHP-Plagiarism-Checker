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
 * Request model for AI Video Detection of a video URL.
 * The result is sent to the webhook when processing is complete.
 * See https://docs.copyleaks.com/reference/actions/ai-video-detector/submit
 */
class CopyleaksAiVideoDetectionRequestModel
{
    /**
     * Publicly accessible URL of the video file to analyze.
     */
    public string $url;

    /**
     * The name of the video file including its extension, at most 255 characters.
     * Supported extensions: .mp4, .avi, .mov, .mkv, .webm, .flv, .wmv, .mpg, .m4v, .3gp, .mxf
     */
    public string $filename;

    /**
     * The AI detection model, for example CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO.
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
     * Headers to add when Copyleaks downloads the video from $url, as [["Header-Name", "Header-Value"], ...].
     * @var string[][]|null
     */
    public ?array $headers;

    /**
     * The HTTP method Copyleaks uses to download the video from $url: 'GET', 'POST' or 'PUT'.
     * Leave null to get the server default ('GET').
     */
    public ?string $verb;

    /**
     * @param string $url Publicly accessible URL of the video file to analyze.
     * @param string $filename The name of the video file including its extension.
     * @param string $model The AI detection model, for example CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO.
     * @param CopyleaksAsyncWebhooks $webhooks Where to send the result.
     * @param bool|null $sandbox Use sandbox mode (free, mock results). null = server default (false).
     * @param string|null $developerPayload A string that Copyleaks includes in the webhook.
     * @param string[][]|null $headers Headers to add when Copyleaks downloads the video.
     * @param string|null $verb 'GET', 'POST' or 'PUT'. null = server default ('GET').
     */
    public function __construct(
        string $url,
        string $filename,
        string $model,
        CopyleaksAsyncWebhooks $webhooks,
        ?bool $sandbox = null,
        ?string $developerPayload = null,
        ?array $headers = null,
        ?string $verb = null
    ) {
        $this->url = $url;
        $this->filename = $filename;
        $this->model = $model;
        $this->webhooks = $webhooks;
        $this->sandbox = $sandbox;
        $this->developerPayload = $developerPayload;
        $this->headers = $headers;
        $this->verb = $verb;
    }
}
