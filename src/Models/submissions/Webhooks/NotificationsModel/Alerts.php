<?php

namespace Copyleaks;

class Alerts
{
    /**
     * @var int|null Scan alert category.
     * Note: Changed to int|null as per C# 'int Category' but your PHP request used '?string $category'.
     * Assuming 'category' is an integer code. If it's truly a string, adjust type accordingly.
     */
    public ?int $category;

    /**
     * @var string|null Scan alert code. The code is unique for each scan alert.
     */
    public ?string $code;

    /**
     * @var string|null Scan alert human-readable title.
     */
    public ?string $title;

    /**
     * @var string|null Provides human-readable information about the scan alert.
     */
    public ?string $message;

    /**
     * @var string|null URL to a resource describing the specific scan alert.
     */
    public ?string $helpLink;

    /**
     * @var int|null Specifies the importance of the scan alert.
     * Note: Changed to int|null as per C# 'int Severity' but your PHP request used '?string $severity'.
     * Assuming 'severity' is an integer code. If it's truly a string, adjust type accordingly.
     */
    public ?int $severity;

    /**
     * @var string|null Additional data about the scan alert. Supplied as a JSON string.
     */
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
