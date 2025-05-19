<?php

namespace Copyleaks;

class Error
{
    /**
     * An optional integer error code associated with the error.
     * @var int|null
     */
    public ?int $code;

    /**
     * An optional descriptive message explaining the error.
     * @var string|null
     */
    public ?string $message;

    /**
     * Constructor to initialize the error details.
     * Both parameters are optional and default to null.
     *
     * @param int|null $code The error code.
     * @param string|null $message The error message.
     */
    public function __construct(?int $code = null, ?string $message = null)
    {
        $this->code = $code;
        $this->message = $message;
    }
}

