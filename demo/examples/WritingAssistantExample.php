<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
use Copyleaks\ScoreWeights;

/**
 * Example demonstrating Writing Assistant functionality
 */
function runWritingAssistantExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $sampleText = "Lions are the only cat that live in groups, called pride. A prides typically consists of a few adult males, several feales, and their offspring. This social structure is essential for hunting and raising young cubs. Female lions, or lionesses are the primary hunters of the prid. They work together in cordinated groups to take down prey usually targeting large herbiores like zbras, wildebeest and buffalo. Their teamwork and strategy during hunts highlight the intelligence and coperation that are key to their survival.";

    $scoreWeights = new ScoreWeights(0.1, 0.2, 0.3, 0.4);

    $submission = new CopyleaksWritingAssistantSubmissionModel($sampleText);
    $submission->sandbox = true;
    $submission->score = $scoreWeights;

    $response = $copyleaks->writingAssistantClient->submitText($authToken, time(), $submission);
    
    logInfo('Writing Assistant Example', $response);
}