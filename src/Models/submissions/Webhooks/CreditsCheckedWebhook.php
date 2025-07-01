<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;
use Copyleaks\ScannedDocument;

class CreditsCheckedWebhook extends StatusWebhook
{
    public ?int $credits;
    public ?ScannedDocument $scannedDocument;

    public function __construct(
        ?int $credits = null,
        ?ScannedDocument $scannedDocument = null
    ) {
        $this->credits = $credits;
        $this->scannedDocument = $scannedDocument;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['credits'] ?? null,
            isset($data['scannedDocument']) ? ScannedDocument::fromArray($data['scannedDocument']) : null
        );
    }
}
