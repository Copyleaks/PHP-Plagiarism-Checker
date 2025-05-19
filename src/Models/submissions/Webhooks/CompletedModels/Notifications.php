<?php

namespace Copyleaks;

class Notifications
{
    public ?array $alerts;

    public function __construct(?array $alerts = [])
    {
        $this->alerts = $alerts;

    }
}

