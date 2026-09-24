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

    /**
     * Returns the first 'suspected-ai-text' alert (CopyleaksAlertCodes::SUSPECTED_AI_TEXT) of the scan.
     * The alerts can be Alerts instances (as fromArray() builds them) or raw alert arrays;
     * a matching raw array is converted with Alerts::fromArray().
     * Returns null when the completed webhook contains no suspected-ai-text alert.
     *
     * @return Alerts|null null when there are no notifications or alerts, or no 'suspected-ai-text' alert.
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
     * Decodes the AI text detection result of the 'suspected-ai-text' alert.
     * Same as getAIDetectionAlert()->getAIDetectionResult().
     *
     * Returns null when:
     * - there is no 'suspected-ai-text' alert (see getAIDetectionAlert());
     * - the alert's additionalData is null, empty, or only NUL and ASCII whitespace characters;
     * - the alert's additionalData is valid JSON but not a JSON object.
     * Use getAIDetectionAlert() to tell a missing alert from an alert without a result.
     *
     * @return CopyleaksAiTextDetectionResponseModel|null the decoded result, or null in the cases listed above.
     * @throws \JsonException when the alert's trimmed additionalData is not valid JSON, or is nested deeper than
     * 512 levels.
     */
    public function getAIDetectionResult(): ?CopyleaksAiTextDetectionResponseModel
    {
        $alert = $this->getAIDetectionAlert();

        return $alert === null ? null : $alert->getAIDetectionResult();
    }
}
