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
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::ADULT_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::TOXIC_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::VIOLENT_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::PROFANITY_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::SELF_HARM_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::HARASSMENT_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::HATE_SPEECH_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::DRUGS_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::FIREARMS_V1),
        new CopyleaksTextModerationLabel(/*label*/CopyleaksTextModerationConstants::CYBERSECURITY_V1)
    ];

    $textModerationRequest = new CopyleaksTextModerationRequestModel(
        /*text*/"This is some text to scan.",
        /*sandbox*/true,
        /*language*/CopyleaksTextModerationLanguages::ENGLISH,
        /*labels*/$labelsArray
    );

    $response = $copyleaks->textModerationClient->submitText(/*authToken*/$authToken, /*scanId*/time(), /*request*/$textModerationRequest);
    $textModerationResponse = CopyleaksTextModerationResponseModel::fromArray(/*data*/json_decode(/*json*/json_encode(/*value*/$response), /*associative*/true));

    logInfo(/*message*/'Text Moderation Example', /*context*/$textModerationResponse);
}