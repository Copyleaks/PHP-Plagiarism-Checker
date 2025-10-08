<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksFileSubmissionModel;
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
 * Example demonstrating how to submit file for plagiarism detection
 */
function runSubmitFileExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken, string $webhookUrl): void
{
    $base64pdfLogo = file_exists(/*filename*/'base64logo.txt') ? file_get_contents(/*filename*/'base64logo.txt') : '';
    
    $webhooks = new SubmissionWebhooks(/*url*/"$webhookUrl/{STATUS}");
    $author = new SubmissionAuthor(/*name*/'php-test');
    $filters = new SubmissionFilter(/*identicalEnabled*/true, /*minorChangesEnabled*/true, /*relatedMeaningEnabled*/true);
    
    $scanningExclude = new SubmissionScanningExclude(/*entryIds*/'php-test-*');
    $copyleaksDB = new SubmissionScanningCopyleaksDB(/*includeMySubmissions*/true, /*includeOthersSubmissions*/true);
    $scanning = new SubmissionScanning(/*internet*/true, /*exclude*/$scanningExclude, /*include*/null, /*copyleaksDB*/$copyleaksDB);
    
    $indexing = new SubmissionIndexing(/*repositories*/(array)[new SubmissionRepository(/*id*/'repoId')]);
    $exclude = new SubmissionExclude(/*quotes*/true, /*references*/true, /*tablesOfContents*/true, /*titles*/true, /*htmlTemplate*/true);
    $pdf = new SubmissionPDF(/*create*/true, /*title*/'title', /*logoImage*/$base64pdfLogo, /*rtl*/false);
    
    $properties = new SubmissionProperties(
        /*webhooks*/$webhooks,
        /*includeHtml*/false,
        /*developerPayload*/null,
        /*sandbox*/true,
        /*expiration*/6,
        /*sensitiveDataProtection*/1,
        /*cheatDetection*/true,
        /*action*/SubmissionActions::Scan,
        /*author*/$author,
        /*filters*/$filters,
        /*scanning*/$scanning,
        /*indexing*/$indexing,
        /*exclude*/$exclude,
        /*pdf*/$pdf
    );
    
    $submission = new CopyleaksFileSubmissionModel(
        /*base64*/"SGVsbG8gV29ybGQ=",
        /*filename*/"php.txt",
        /*properties*/$properties
    );

    $copyleaks->submitFile(/*authToken*/$authToken, /*scanId*/time(), /*submission*/$submission);
    
    logInfo(/*message*/"-Submit File Example-");
}