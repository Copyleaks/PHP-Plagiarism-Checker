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
     * A null alert means the scan produced no AI alert. It does not by itself prove that AI detection ran:
     * check the scan's aiGeneratedText.detect setting and the category 2 failure alerts
     * (for example CopyleaksAlertCodes::AI_DETECTION_FAILED).
     *
     * @return Alerts|null
     */
    public function getAIDetectionAlert(): ?Alerts
    {
        if ($this->notifications === null || !is_array($this->notifications->alerts)) {
            return null;
        }

        foreach ($this->notifications->alerts as $alert) {
            if ($alert instanceof Alerts && $alert->code === CopyleaksAlertCodes::SUSPECTED_AI_TEXT) {
                return $alert;
            }
        }

        return null;
    }

    /**
     * Decodes the AI text detection result of the 'suspected-ai-text' alert.
     * Same as getAIDetectionAlert()->getAIDetectionResult(), and null when there is no such alert.
     * It is also null when the alert has no additionalData, so use getAIDetectionAlert() to check for the alert.
     *
     * @return CopyleaksAiTextDetectionResponseModel|null
     * @throws \JsonException when the alert's additionalData is not valid JSON.
     */
    public function getAIDetectionResult(): ?CopyleaksAiTextDetectionResponseModel
    {
        $alert = $this->getAIDetectionAlert();

        return $alert === null ? null : $alert->getAIDetectionResult();
    }
}
