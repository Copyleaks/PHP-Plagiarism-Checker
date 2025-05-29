<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;
use Copyleaks\Results;
use Copyleaks\Notifications;
use Copyleaks\ScannedDocument;

class CompletedWebhook extends StatusWebhook
{
    public ?Results $results;
    public ?Notifications $notifications;
    public ?ScannedDocument $scannedDocument;

    public function __construct(
        ?Results $results = null,
        ?Notifications $notifications = null,
        ?ScannedDocument $scannedDocument = null
    ) {
        $this->results = $results;
        $this->notifications = $notifications;
        $this->scannedDocument = $scannedDocument;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            isset($data['results']) ? Results::fromArray($data['results']) : null,
            isset($data['notifications']) ? Notifications::fromArray($data['notifications']) : null,
            isset($data['scannedDocument']) ? ScannedDocument::fromArray($data['scannedDocument']) : null
        );
    }
}
