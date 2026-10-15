<?php

namespace Copyleaks;

use Copyleaks\StatusWebhook;
use Copyleaks\Results;
use Copyleaks\Notifications;
use Copyleaks\ScannedDocument;
use Copyleaks\Alerts;
use Copyleaks\CopyleaksAlertCodes;
use Copyleaks\CopyleaksAiTextDetectionResponseModel;

class CompletedWebhook extends StatusWebhook
{
    public ?Results $results;
    public ?Notifications $notifications;
    public ?ScannedDocument $scannedDocument;

    /** Citation Checker summary; only when properties.references.validate was enabled. */
    public ?ReferencesValidation $referencesValidation;

    public function __construct(
        ?Results $results = null,
        ?Notifications $notifications = null,
        ?ScannedDocument $scannedDocument = null,
        ?ReferencesValidation $referencesValidation = null
    ) {
        $this->results = $results;
        $this->notifications = $notifications;
        $this->scannedDocument = $scannedDocument;
        $this->referencesValidation = $referencesValidation;
    }

    public static function fromArray(array $data): self
    {
        $webhook = new self(
            isset($data['results']) ? Results::fromArray($data['results']) : null,
            isset($data['notifications']) ? Notifications::fromArray($data['notifications']) : null,
            isset($data['scannedDocument']) ? ScannedDocument::fromArray($data['scannedDocument']) : null,
            is_array($data['referencesValidation'] ?? null) ? ReferencesValidation::fromArray($data['referencesValidation']) : null
        );
        self::withBaseFields($webhook, $data);
        return $webhook;
    }

    /**
     * The first 'suspected-ai-text' alert. A raw alert array is converted with Alerts::fromArray().
     * @return Alerts|null null when there is no such alert.
     */
    public function getAIDetectionAlert(): ?Alerts
    {
        if ($this->notifications === null || !is_array($this->notifications->alerts)) {
            return null;
        }

        foreach ($this->notifications->alerts as $alert) {
            if ($alert instanceof Alerts) {
                if ($alert->code === CopyleaksAlertCodes::SUSPECTED_AI_TEXT) {
                    return $alert;
                }
            } elseif (is_array($alert) && ($alert['code'] ?? null) === CopyleaksAlertCodes::SUSPECTED_AI_TEXT) {
                return Alerts::fromArray($alert);
            }
        }

        return null;
    }

    /**
     * Same as getAIDetectionAlert()->getAIDetectionResult(), and also null when there is no such alert.
     * Use getAIDetectionAlert() to tell a missing alert from an alert without a result.
     * @return CopyleaksAiTextDetectionResponseModel|null
     * @throws \JsonException When the alert's additionalData is not valid JSON or is nested deeper than 512 levels.
     */
    public function getAIDetectionResult(): ?CopyleaksAiTextDetectionResponseModel
    {
        $alert = $this->getAIDetectionAlert();

        return $alert === null ? null : $alert->getAIDetectionResult();
    }
}
