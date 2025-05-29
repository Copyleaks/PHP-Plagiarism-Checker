<?php

namespace Copyleaks;

class Error
{
    public ?int $code;
    public ?string $message;

    public function __construct(?int $code = null, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['code']) ? (int)$data['code'] : null,
            $data['message'] ?? null
        );
    }
}
