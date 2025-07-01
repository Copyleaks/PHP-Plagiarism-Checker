<?php

namespace Copyleaks;

class Tags
{
   /**
     * @var string|null Unique ID that signifies the type of result tag.
     */
    public ?string $code;

    /**
     * @var string|null A simple name for this tag.
     */
    public ?string $title;

    /**
     * @var string|null Short text describing this tag.
     */
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
