<?php

namespace Copyleaks;


class Webhook
{
    /**
     * The developer-specific payload string.
     * This property is public, allowing direct access.
     * @var string
     */
    public string $developerPayload;

    /**
     * Constructor to initialize the public developerPayload property.
     *
     * @param string $payload The initial developer payload.
     */
    public function __construct(string $payload)
    {
        $this->developerPayload = $payload;
    }

}
