<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;

/**
 * Example demonstrating AI detection for natural language text
 */
function runAiDetectionNaturalLanguageExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $sampleText = "Lions are social animals, living in groups called prides, typically consisting of several females, their offspring, and a few males. Female lions are the primary hunters, working together to catch prey. Lions are known for their strength, teamwork, and complex social structures.";

    $submission = new CopyleaksNaturalLanguageSubmissionModel($sampleText);
    $submission->sandbox = true;

    $response = $copyleaks->aiDetectionClient->submitNaturalLanguage($authToken, time(), $submission);
    
    logInfo('AI Detection - Natural Language Example', $response);
}