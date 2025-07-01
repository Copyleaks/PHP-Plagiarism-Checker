<?php

namespace Copyleaks;

use Copyleaks\Webhook;
use Copyleaks\Task;

class ExportCompletedWebhook extends Webhook
{
    public ?bool $completed;
    /** @var Task[]|null */
    public ?array $tasks;

    public function __construct(
        ?bool $completed = null,
        ?array $tasks = null
    ) {
        $this->completed = $completed;
        $this->tasks = $tasks;
    }

    public static function fromArray(array $data): self
    {
        $tasks = null;

        if (!empty($data['tasks']) && is_array($data['tasks'])) {
            $tasks = array_map(
                fn($taskData) => Task::fromArray($taskData),
                $data['tasks']
            );
        }

        return new self(
            $data['completed'] ?? null,
            $tasks
        );
    }
}
