<?php

namespace Copyleaks;

/**
 * A parameter that caused an error (error.details).
 */
class ErrorDetail
{
    /** @var string|null */
    public ?string $param;

    /** @var string|null What is wrong with the parameter. */
    public ?string $message;

    public function __construct(?string $param = null, ?string $message = null)
    {
        $this->param = $param;
        $this->message = $message;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            is_scalar($data['param'] ?? null) ? (string) $data['param'] : null,
            is_scalar($data['message'] ?? null) ? (string) $data['message'] : null
        );
    }
}
