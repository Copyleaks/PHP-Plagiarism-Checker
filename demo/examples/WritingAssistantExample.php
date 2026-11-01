<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
use Copyleaks\ScoreWeights;

function runWritingAssistantExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $sampleText = "Lions are the only cat that live in groups, called pride. A prides typically consists of a few adult males, several feales, and their offspring. This social structure is essential for hunting and raising young cubs. Female lions, or lionesses are the primary hunters of the prid. They work together in cordinated groups to take down prey usually targeting large herbiores like zbras, wildebeest and buffalo. Their teamwork and strategy during hunts highlight the intelligence and coperation that are key to their survival.";

    $scoreWeights = new ScoreWeights(/*grammarScoreWeight*/0.1, /*mechanicsScoreWeight*/0.2, /*sentenceStructureScoreWeight*/0.3, /*wordChoiceScoreWeight*/0.4);

    $submission = new CopyleaksWritingAssistantSubmissionModel(/*text*/$sampleText);
    $submission->sandbox = true;
    $submission->score = $scoreWeights;

    $response = $copyleaks->writingAssistantClient->submitText(/*authToken*/$authToken, /*scanId*/time(), /*submission*/$submission);
    
    logInfo(/*title*/'Writing Assistant Example', /*info*/$response);
}