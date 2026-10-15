<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;

class IndexedWebhook extends StatusWebhook
{
    public function __construct()
    {
    }

    public static function fromArray(array $data): self
    {
        $webhook = new self();
        self::withBaseFields($webhook, $data);
        return $webhook;
    }
}
