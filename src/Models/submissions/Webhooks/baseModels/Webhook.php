<?php

namespace Copyleaks;

class Webhook
{
    /**
    * @var string|null The developer payload that was provided in the submit method.
    */
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
     * Copies the fields shared by every webhook (developerPayload, and status for status webhooks)
     * from the decoded webhook body to a model built by a subclass's fromArray().
     *
     * @param Webhook $webhook The model to fill.
     * @param array $data The decoded webhook body.
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
