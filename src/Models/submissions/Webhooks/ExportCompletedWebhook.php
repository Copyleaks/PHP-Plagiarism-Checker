<?php


namespace Copyleaks;
use Copyleaks\Webhook;
use Copyleaks\Task;

class ExportCompletedWebhook extends Webhook{

    public ?bool  $completed;
    public ?array $tasks;


    public function __construct(
        ?bool  $completed = null,
        ?array $tasks = null
    ) {
        $this->completed = $completed;
        $this->tasks = $tasks;
    }
}