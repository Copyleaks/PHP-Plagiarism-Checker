<?php

namespace Copyleaks;

use Copyleaks\Webhook;
use Copyleaks\NewResultScore;

class NewResultWebhook extends Webhook
{
    public ?NewResultScore $score;
    public ?array $internet;
    public ?array $database;
    public ?array $batch;
    public ?array $repositories;

    public function __construct(
        ?NewResultScore $score = null,
        ?array $internet = null,
        ?array $database = null,
        ?array $batch = null,
        ?array $repositories = null
    ) {
        $this->score = $score;
        $this->internet = $internet;
        $this->database = $database;
        $this->batch = $batch;
        $this->repositories = $repositories;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['score']) ? NewResultScore::fromArray($data['score']) : null,
            $data['internet'] ?? null,
            $data['database'] ?? null,
            $data['batch'] ?? null,
            $data['repositories'] ?? null
        );
    }
}
