<?php

namespace Copyleaks;

/**
 * Represents a webhook payload with an additional status code.
 * Extends the base Webhook class.
 */
class StatusWebhook extends Webhook 
{
    /**
     * The status code associated with this webhook event.
     * This property is public, allowing direct access.
     * @var int
     */
    public int $status;

    /**
     * Constructor to initialize the parent payload and the status property.
     *
     * @param string $payload The developer payload (required by the parent Webhook constructor).
     * @param int $status The status code for this specific webhook.
     */
    public function __construct(string $payload, int $status)
    {
        // initialize the $developerPayload property.
        parent::__construct($payload);

        // Initialize the property specific to this StatusWebhook class.
        $this->status = $status;
    }
}