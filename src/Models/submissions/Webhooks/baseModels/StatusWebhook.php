<?php

namespace Copyleaks;

class StatusWebhook extends Webhook
{
    public int $status;

    public function __construct(string $payload, int $status)
    {
        parent::__construct($payload);
        $this->status = $status;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['developerPayload'] ?? '',
            $data['status'] ?? 0
        );
    }
}
