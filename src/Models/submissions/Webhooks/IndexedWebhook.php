<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;

class IndexedWebhook extends StatusWebhook
{
    public function __construct()
    {
        // No custom properties at the moment
    }

    public static function fromArray(array $data): self
    {
        // No specific fields to deserialize currently
        return new self();
    }
}
