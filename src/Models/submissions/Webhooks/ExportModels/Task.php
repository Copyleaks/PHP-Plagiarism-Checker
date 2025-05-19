<?php

namespace Copyleaks;
/**
 * Represents the status result of checking a specific endpoint.
 * Properties are public, nullable, and initialized via the constructor.
 */
class Task
{
    /**
     * The specific endpoint URL.
     * @var string|null
     */
    public ?string $endpoint;

    /**
     * Indicates if the checked endpoint is considered healthy.
     * True means healthy, false means unhealthy.
     * @var bool|null
     */
    public ?bool $isHealthy;

    /**
     * The HTTP status code returned when accessing the endpoint.
     * E.g., 200, 404, 500, 503 etc.
     * @var int|null
     */
    public ?int $httpStatusCode;

    /**
     * Constructor to initialize the endpoint status details.
     * All parameters are optional and default to null.
     *
     * @param string|null $endpoint The endpoint.
     * @param bool|null $isHealthy The health status (true for healthy, false for unhealthy)
     * @param int|null $httpStatusCode The HTTP status code received.
     */
    public function __construct(
        ?string $endpoint = null,
        ?bool $isHealthy = null,
        ?int $httpStatusCode = null
    ) {
        $this->endpoint = $endpoint;
        $this->isHealthy = $isHealthy;
        $this->httpStatusCode = $httpStatusCode;
    }
}
