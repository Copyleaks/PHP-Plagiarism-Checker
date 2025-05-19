<?php


namespace Copyleaks;
use Copyleaks\Webhook;
use Copyleaks\Task;

class NewResultWebhook extends Webhook{

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
        $this->score = $internet;
        $this->score = $database;
        $this->score = $batch;
        $this->score = $repositories;

    }
}