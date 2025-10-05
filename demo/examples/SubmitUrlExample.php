<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksURLSubmissionModel;
use Copyleaks\SubmissionActions;
use Copyleaks\SubmissionAuthor;
use Copyleaks\SubmissionExclude;
use Copyleaks\SubmissionFilter;
use Copyleaks\SubmissionIndexing;
use Copyleaks\SubmissionPDF;
use Copyleaks\SubmissionProperties;
use Copyleaks\SubmissionRepository;
use Copyleaks\SubmissionScanning;
use Copyleaks\SubmissionScanningCopyleaksDB;
use Copyleaks\SubmissionScanningExclude;
use Copyleaks\SubmissionWebhooks;

/**
 * Example demonstrating how to submit URL for plagiarism detection
 */
function runSubmitUrlExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken, string $webhookUrl): void
{
    $base64pdfLogo = file_exists('base64logo.txt') ? file_get_contents('base64logo.txt') : '';
    
    $submission = new CopyleaksURLSubmissionModel(
        "https://copyleaks.com",
        new SubmissionProperties(
            new SubmissionWebhooks("$webhookUrl/{STATUS}"),
            false,
            null,
            true,
            6,
            1,
            true,
            SubmissionActions::Scan,
            new SubmissionAuthor('php-test'),
            new SubmissionFilter(true, true, true),
            new SubmissionScanning(true, new SubmissionScanningExclude('php-test-*'), null, new SubmissionScanningCopyleaksDB(true, true)),
            new SubmissionIndexing((array)[new SubmissionRepository('repoId')]),
            new SubmissionExclude(true, true, true, true, true),
            new SubmissionPDF(true, 'title', $base64pdfLogo, false)
        )
    );

    $copyleaks->submitUrl($authToken, time(), $submission);
    
    logInfo("-Submit URL Example-");
}