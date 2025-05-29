<?php

namespace Copyleaks;

class Webhook
{
    public string $developerPayload;

    public function __construct(string $payload)
    {
        $this->developerPayload = $payload;
    }

    public static function fromArray(array $data): self
    {
        return new self($data['developerPayload'] ?? '');
    }
}
