<?php
namespace Copyleaks;
use Copyleaks\StatusWebhook;
use Copyleaks\Results;
use Copyleaks\Notifications;
use Copyleaks\ScannedDocument;

class CompletedWebhook extends StatusWebhook{

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
}