<?php

namespace Copyleaks;

class Task
{
    public ?string $endpoint;
    public ?bool $isHealthy;
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
