<?php

namespace Copyleaks;

/**
 * Represents an alert model.
 * Properties are public, nullable strings, and initialized via the constructor.
 */
class Alerts
{
    /**
     * The category of the error.
     * @var string|null
     */
    public ?string $category;

    /**
     * A specific error code string, which might be more granular than an HTTP status.
     * @var string|null
     */
    public ?string $code;

    /**
     * A short, human-readable title summarizing the error.
     * @var string|null
     */
    public ?string $title;

    /**
     * A detailed message explaining the error.
     * @var string|null
     */
    public ?string $message;

    /**
     * A URL pointing to documentation or help resources related to this specific error.
     * @var string|null
     */
    public ?string $helpLink;

    /**
     * The severity level of the error.
     * @var string|null
     */
    public ?string $severity;

    /**
     * Additional structured data related to the error context, provided as a JSON string.
     * @var string|null
     */
    public ?string $additionalData;

    /**
     * Constructor to initialize all detailed error properties.
     * All parameters are optional and default to null.
     *
     * @param string|null $category The error category.
     * @param string|null $code The specific error code string.
     * @param string|null $title A short title for the error.
     * @param string|null $message A detailed error message.
     * @param string|null $helpLink A URL for help documentation.
     * @param string|null $severity The severity level.
     * @param string|null $additionalData Additional contextual data (e.g., JSON string).
     */
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

}
