<?php

namespace Copyleaks;

class Notifications
{
    public ?array $alerts;

    public function __construct(?array $alerts = [])
    {
        $this->alerts = $alerts;
    }

    public static function fromArray(array $data): self
    {
        $alerts = null;

        if (isset($data['alerts']) && is_array($data['alerts'])) {
            $alerts = array_map(fn($item) => Alerts::fromArray($item), $data['alerts']);
        }

        return new self($alerts);
    }
}
