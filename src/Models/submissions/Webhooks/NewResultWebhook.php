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

    /** @var InternalAIData[]|null Internal AI Source Match results. */
    public ?array $internalAIData;

    public function __construct(
        ?NewResultScore $score = null,
        ?array $internet = null,
        ?array $database = null,
        ?array $batch = null,
        ?array $repositories = null,
        ?array $internalAIData = null
    ) {
        $this->score = $score;
        $this->internet = $internet;
        $this->database = $database;
        $this->batch = $batch;
        $this->repositories = $repositories;
        $this->internalAIData = $internalAIData;
    }

    public static function fromArray(array $data): self
    {
        $webhook = new self(
            isset($data['score']) ? NewResultScore::fromArray($data['score']) : null,
            $data['internet'] ?? null,
            $data['database'] ?? null,
            $data['batch'] ?? null,
            $data['repositories'] ?? null,
            InternalAIData::listFromArray($data['internalAIData'] ?? null)
        );
        self::withBaseFields($webhook, $data);
        return $webhook;
    }
}
