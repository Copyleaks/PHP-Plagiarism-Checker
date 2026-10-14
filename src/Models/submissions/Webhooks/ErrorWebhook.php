<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;
use Copyleaks\Error;

class ErrorWebhook extends StatusWebhook
{
    public ?Error $error;

    public function __construct(
        ?Error $error = null
    ) {
        $this->error = $error;
    }

    public static function fromArray(array $data): self
    {
        $webhook = new self(
            isset($data['error']) ? Error::fromArray($data['error']) : null
        );
        self::withBaseFields($webhook, $data);
        return $webhook;
    }
}
