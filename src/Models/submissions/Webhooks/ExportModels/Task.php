<?php

namespace Copyleaks;

class Task
{
    /** @var string */
    public string $endpoint;

    /** @var bool Whether the task completed without internal errors on the Copyleaks side. */
    public bool $isHealthy;

    /** @var int|null Status code returned by your server; null when isHealthy is false. */
    public ?int $httpStatusCode;

    public function __construct(
        ?string $endpoint = null,
        ?bool $isHealthy = null,
        ?int $httpStatusCode = null
    ) {
        $this->endpoint = $endpoint;
        $this->isHealthy = $isHealthy;
        $this->httpStatusCode = $httpStatusCode;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['endpoint'] ?? null,
            isset($data['isHealthy']) ? (bool)$data['isHealthy'] : null,
            isset($data['httpStatusCode']) ? (int)$data['httpStatusCode'] : null
        );
    }
}
