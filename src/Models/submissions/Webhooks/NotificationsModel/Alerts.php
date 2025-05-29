<?php

namespace Copyleaks;

class Alerts
{
    public ?string $category;
    public ?string $code;
    public ?string $title;
    public ?string $message;
    public ?string $helpLink;
    public ?string $severity;
    public ?string $additionalData;

    public function __construct(
        ?string $category = null,
        ?string $code = null,
        ?string $title = null,
        ?string $message = null,
        ?string $helpLink = null,
        ?string $severity = null,
        ?string $additionalData = null
    ) {
        $this->category = $category;
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->helpLink = $helpLink;
        $this->severity = $severity;
        $this->additionalData = $additionalData;
    }

    public static function fromArray(array $data): Alerts
    {
        return new self(
            $data['category'] ?? null,
            $data['code'] ?? null,
            $data['title'] ?? null,
            $data['message'] ?? null,
            $data['helpLink'] ?? null,
            $data['severity'] ?? null,
            $data['additionalData'] ?? null
        );
    }
}
