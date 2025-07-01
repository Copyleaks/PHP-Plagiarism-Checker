<?php

namespace Copyleaks;

class Task
{
    /**
     * @var string The endpoint address of the export task.
     */
    public string $endpoint;

    /**
     * @var bool This flag gives an indication whether the scan was completed without internal errors on the Copyleaks side.
     */
    public bool $isHealthy;

    /**
     * @var int|null The status code reported by the customer servers.
     * If the tasks.isHealthy is equal to false - this field will be null.
     */
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
