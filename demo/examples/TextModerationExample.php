<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksTextModerationConstants;
use Copyleaks\CopyleaksTextModerationLabel;
use Copyleaks\CopyleaksTextModerationLanguages;
use Copyleaks\CopyleaksTextModerationRequestModel;
use Copyleaks\CopyleaksTextModerationResponseModel;

/**
 * Example demonstrating text moderation functionality
 */
function runTextModerationExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $labelsArray = [
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::ADULT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::TOXIC_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::VIOLENT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::PROFANITY_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::SELF_HARM_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::HARASSMENT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::HATE_SPEECH_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::DRUGS_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::FIREARMS_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::CYBERSECURITY_V1)
    ];

    $textModerationRequest = new CopyleaksTextModerationRequestModel(
        "This is some text to scan.", // text
        true,                        // sandbox mode
        CopyleaksTextModerationLanguages::ENGLISH, // language
        $labelsArray
    );

    $response = $copyleaks->textModerationClient->submitText($authToken, time(), $textModerationRequest);
    $textModerationResponse = CopyleaksTextModerationResponseModel::fromArray(json_decode(json_encode($response), true));

    logInfo('Text Moderation Example', $textModerationResponse);
}