<?php

namespace Copyleaks;

class Error
{
    /** @var int|null Failure reason, see the error table in the API docs. */
    public ?int $code;

    /** @var string|null */
    public ?string $message;

    /** @var string|null E.g. "invalid_request_error" or "payment_error". */
    public ?string $type;

    /** @var string|null Machine-readable, e.g. "insufficient_credits". */
    public ?string $id;

    /** @var string|null Documentation page of this error. */
    public ?string $url;

    /** @var ErrorDetail[]|null The parameters that caused the error, when known. */
    public ?array $details;

    public function __construct(
        ?int $code = null,
        ?string $message = null,
        ?string $type = null,
        ?string $id = null,
        ?string $url = null,
        ?array $details = null
    ) {
        $this->code = $code;
        $this->message = $message;
        $this->type = $type;
        $this->id = $id;
        $this->url = $url;
        $this->details = $details;
    }

    public static function fromArray(array $data): self
    {
        $details = null;
        if (is_array($data['details'] ?? null)) {
            $details = [];
            foreach ($data['details'] as $detail) {
                if (is_array($detail)) {
                    $details[] = ErrorDetail::fromArray($detail);
                }
            }
        }

        return new self(
            isset($data['code']) ? (int)$data['code'] : null,
            $data['message'] ?? null,
            is_scalar($data['type'] ?? null) ? (string) $data['type'] : null,
            is_scalar($data['id'] ?? null) ? (string) $data['id'] : null,
            is_scalar($data['url'] ?? null) ? (string) $data['url'] : null,
            $details
        );
    }
}
