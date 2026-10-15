<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksTextModerationConstants;
use Copyleaks\CopyleaksTextModerationLabel;
use Copyleaks\CopyleaksTextModerationLanguages;
use Copyleaks\CopyleaksTextModerationRequestModel;
use Copyleaks\CopyleaksTextModerationResponseModel;

function runTextModerationExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $labelsArray = [
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::ADULT_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::TOXIC_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::VIOLENT_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::PROFANITY_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::SELF_HARM_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::HARASSMENT_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::HATE_SPEECH_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::DRUGS_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::FIREARMS_V1),
        new CopyleaksTextModerationLabel(/*id*/CopyleaksTextModerationConstants::CYBERSECURITY_V1)
    ];

    $textModerationRequest = new CopyleaksTextModerationRequestModel(
        /*text*/"This is some text to scan.",
        /*sandbox*/true,
        /*language*/CopyleaksTextModerationLanguages::ENGLISH,
        /*labels*/$labelsArray
    );

    $response = $copyleaks->textModerationClient->submitText(/*authToken*/$authToken, /*scanId*/time(), /*submission*/$textModerationRequest);
    $textModerationResponse = CopyleaksTextModerationResponseModel::fromArray(/*data*/json_decode(/*json*/json_encode(/*value*/$response), /*associative*/true));

    logInfo(/*title*/'Text Moderation Example', /*info*/$textModerationResponse);
}