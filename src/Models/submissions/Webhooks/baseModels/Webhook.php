<?php

namespace Copyleaks;

class Webhook
{
    /** @var string|null From the submit request. */
    public string $developerPayload;

    public function __construct(string $payload)
    {
        $this->developerPayload = $payload;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['developerPayload'] ?? '');
    }

    /**
     * Copy developerPayload (and status, for status webhooks) from the decoded body to a subclass's model.
     * @param Webhook $webhook
     * @param array $data
     * @return Webhook The same model.
     */
    protected static function withBaseFields(Webhook $webhook, array $data): Webhook
    {
        $webhook->developerPayload = is_scalar($data['developerPayload'] ?? null) ? (string) $data['developerPayload'] : '';
        if ($webhook instanceof StatusWebhook) {
            $webhook->status = is_numeric($data['status'] ?? null) ? (int) $data['status'] : 0;
        }
        return $webhook;
    }
}
