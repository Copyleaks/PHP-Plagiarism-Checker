<?php

namespace Copyleaks;

/**
 * AI Video Detection of a video URL; the result is sent to the webhook.
 * See https://docs.copyleaks.com/reference/actions/ai-video-detector/submit
 */
class CopyleaksAiVideoDetectionRequestModel
{
    /** Publicly accessible video URL. */
    public string $url;

    /** File name with extension (.mp4, .avi, .mov, .mkv, .webm, .flv, .wmv, .mpg, .m4v, .3gp, .mxf), max 255 chars. */
    public string $filename;

    /** E.g. CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO. */
    public string $model;

    /** Where to send the result. */
    public CopyleaksAsyncWebhooks $webhooks;

    /** Return mock results without using credits. null = server default (false). */
    public ?bool $sandbox;

    /** Returned in the webhook, to match the result with your own records. */
    public ?string $developerPayload;

    /**
     * Headers for downloading the video from $url, as [["Header-Name", "Header-Value"], ...].
     * @var string[][]|null
     */
    public ?array $headers;

    /** HTTP method for downloading the video from $url: 'GET', 'POST' or 'PUT'. null = server default ('GET'). */
    public ?string $verb;

    /**
     * @param string $url
     * @param string $filename
     * @param string $model
     * @param CopyleaksAsyncWebhooks $webhooks
     * @param bool|null $sandbox
     * @param string|null $developerPayload
     * @param string[][]|null $headers
     * @param string|null $verb
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
