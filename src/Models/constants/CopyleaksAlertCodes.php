<?php

namespace Copyleaks;

/**
 * Scan alert codes in the completed webhook (notifications.alerts[].code).
 */
class CopyleaksAlertCodes
{
    /**
     * AI-generated text was detected (category 2, severity 4). additionalData holds the AI text detection result
     * as a JSON string.
     */
    public const SUSPECTED_AI_TEXT = "suspected-ai-text";
}

?>