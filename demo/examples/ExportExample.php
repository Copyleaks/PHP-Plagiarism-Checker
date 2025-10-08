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
    $exportResults = new ExportResults(
        /*id*/"2a1b402420", 
        /*endpoint*/"$webhookUrl/export-webhook/result/2a1b402420", 
        /*verb*/"POST", 
        /*headers*/array(array("key", "value"))
    );
    
    $crawledVersion = new ExportCrawledVersion(
        /*endpoint*/"$webhookUrl/export-webhook/crawled-version", 
        /*verb*/"POST", 
        /*headers*/array(array("key", "value"))
    );
    
    $model = new CopyleaksExportModel(
        /*completionWebhook*/"$webhookUrl/export-webhook",
        /*results*/array($exportResults),
        /*crawledVersion*/$crawledVersion
    );
    
    $exportedScanId = "1611042365";
    $copyleaks->export(/*authToken*/$authToken, /*scanId*/$exportedScanId, /*exportId*/$exportedScanId, /*model*/$model);
    
    logInfo(/*message*/"-Export Example-");
}