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
    $base64pdfLogo = file_exists(/*filename*/'base64logo.txt') ? file_get_contents(/*filename*/'base64logo.txt') : '';
    
    // SubmissionWebhooks parameters
    $webhookUrlWithStatus = "$webhookUrl/{STATUS}";
    
    // SubmissionAuthor parameters
    $authorName = 'php-test';
    
    // SubmissionFilter parameters
    $identicalEnabled = true;
    $minorChangesEnabled = true;
    $relatedMeaningEnabled = true;
    
    // SubmissionScanningExclude parameters
    $excludeEntryIds = 'php-test-*';
    
    // SubmissionScanningCopyleaksDB parameters
    $includeMySubmissions = true;
    $includeOthersSubmissions = true;
    
    // SubmissionScanning parameters
    $scanInternet = true;
    $scanningExclude = new SubmissionScanningExclude(/*entryIds*/$excludeEntryIds);
    $repositories = null;
    $copyleaksDB = new SubmissionScanningCopyleaksDB(/*includeMySubmissions*/$includeMySubmissions, /*includeOthersSubmissions*/$includeOthersSubmissions);
    
    // SubmissionRepository parameters
    $repositoryId = 'repoId';
    
    // SubmissionIndexing parameters
    $repositories = (array)[new SubmissionRepository(/*id*/$repositoryId)];
    
    // SubmissionExclude parameters
    $excludeQuotes = true;
    $excludeReferences = true;
    $excludeTablesOfContents = true;
    $excludeTitles = true;
    $excludeHtmlTemplate = true;
    
    // SubmissionPDF parameters
    $createPdf = true;
    $pdfTitle = 'title';
    $pdfLogoImage = $base64pdfLogo;
    $pdfRtl = false;
    
    // SubmissionProperties parameters
    $webhooks = new SubmissionWebhooks(/*url*/$webhookUrlWithStatus);
    $includeHtml = false;
    $developerPayload = null;
    $sandbox = true;
    $expiration = 6;
    $sensitiveDataProtection = 1;
    $cheatDetection = true;
    $action = SubmissionActions::Scan;
    $author = new SubmissionAuthor(/*name*/$authorName);
    $filters = new SubmissionFilter(/*identicalEnabled*/$identicalEnabled, /*minorChangesEnabled*/$minorChangesEnabled, /*relatedMeaningEnabled*/$relatedMeaningEnabled);
    $scanning = new SubmissionScanning(/*internet*/$scanInternet, /*exclude*/$scanningExclude, /*repositories*/$repositories, /*copyleaksDB*/$copyleaksDB);
    $indexing = new SubmissionIndexing(/*repositories*/$repositories);
    $exclude = new SubmissionExclude(/*quotes*/$excludeQuotes, /*references*/$excludeReferences, /*tablesOfContents*/$excludeTablesOfContents, /*titles*/$excludeTitles, /*htmlTemplate*/$excludeHtmlTemplate);
    $pdf = new SubmissionPDF(/*create*/$createPdf, /*title*/$pdfTitle, /*logoImage*/$pdfLogoImage, /*rtl*/$pdfRtl);
    
    // CopyleaksURLSubmissionModel parameters
    $url = "https://copyleaks.com";
    $properties = new SubmissionProperties(
        /*webhooks*/$webhooks,
        /*includeHtml*/$includeHtml,
        /*developerPayload*/$developerPayload,
        /*sandbox*/$sandbox,
        /*expiration*/$expiration,
        /*sensitiveDataProtection*/$sensitiveDataProtection,
        /*cheatDetection*/$cheatDetection,
        /*action*/$action,
        /*author*/$author,
        /*filters*/$filters,
        /*scanning*/$scanning,
        /*indexing*/$indexing,
        /*exclude*/$exclude,
        /*pdf*/$pdf
    );
    
    $submission = new CopyleaksURLSubmissionModel(
        /*url*/$url,
        /*properties*/$properties
    );

    $scanId = time();
    $copyleaks->submitUrl(/*authToken*/$authToken, /*scanId*/$scanId, /*submission*/$submission);
    
    logInfo(/*message*/"-Submit URL Example-");
}