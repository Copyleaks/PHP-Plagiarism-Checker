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
}
