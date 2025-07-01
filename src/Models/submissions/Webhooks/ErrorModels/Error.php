<?php

namespace Copyleaks;

class Error
{
    /**
     * @var int|null Error code that represents the reason for failure.
     * Refer to the full error table for details.
     */
    public ?int $code;

    /**
     * @var string|null Error message that represents the reason for failure.
     */
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
