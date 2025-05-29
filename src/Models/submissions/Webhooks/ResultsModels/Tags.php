<?php

namespace Copyleaks;

class Tags
{
    public ?string $code;
    public ?string $title;
    public ?string $description;

    public function __construct(
        ?string $code = null,
        ?string $title = null,
        ?string $description = null
    ) {
        $this->code = $code;
        $this->title = $title;
        $this->description = $description;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code'] ?? null,
            $data['title'] ?? null,
            $data['description'] ?? null
        );
    }
}
