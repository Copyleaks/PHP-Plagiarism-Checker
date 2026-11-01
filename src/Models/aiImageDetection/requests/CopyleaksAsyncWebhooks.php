<?php

namespace Copyleaks;

/**
 * Webhook settings of an asynchronous detection (AI Image Detection async, AI Video Detection).
 */
class CopyleaksAsyncWebhooks
{
    /** Receives the detection result (HTTP POST) when processing is complete. */
    public string $url;

    /**
     * Headers to add to the webhook request, as [["Header-Name", "Header-Value"], ...].
     * @var string[][]|null
     */
    public ?array $headers;

    /**
     * @param string $url
     * @param string[][]|null $headers
     */
    public function __construct(string $url, ?array $headers = null)
    {
        $this->url = $url;
        $this->headers = $headers;
    }
}
