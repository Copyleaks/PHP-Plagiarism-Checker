<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\ExportCrawledVersion;
use Copyleaks\ExportResults;

/**
 * Example demonstrating how to export scan results
 */
function runExportExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken, string $webhookUrl): void
{
    // ExportResults parameters
    $resultId = "2a1b402420";
    $resultEndpoint = "$webhookUrl/export-webhook/result/2a1b402420";
    $resultVerb = "POST";
    $resultHeaders = array(array("key", "value"));
    
    // ExportCrawledVersion parameters
    $crawledVersionEndpoint = "$webhookUrl/export-webhook/crawled-version";
    $crawledVersionVerb = "POST";
    $crawledVersionHeaders = array(array("key", "value"));
    
    // CopyleaksExportModel parameters
    $completionWebhook = "$webhookUrl/export-webhook";
    $exportResults = new ExportResults(/*id*/$resultId, /*endpoint*/$resultEndpoint, /*verb*/$resultVerb, /*headers*/$resultHeaders);
    $crawledVersion = new ExportCrawledVersion(/*endpoint*/$crawledVersionEndpoint, /*verb*/$crawledVersionVerb, /*headers*/$crawledVersionHeaders);
    
    $model = new CopyleaksExportModel(
        /*completionWebhook*/$completionWebhook,
        /*results*/array($exportResults),
        /*crawledVersion*/$crawledVersion
    );
    
    $exportedScanId = "1611042365";
    $copyleaks->export(/*authToken*/$authToken, /*scanId*/$exportedScanId, /*exportId*/$exportedScanId, /*model*/$model);
    
    logInfo(/*message*/"-Export Example-");
}