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
    $model = new CopyleaksExportModel(
        "$webhookUrl/export-webhook",
        array(new ExportResults("2a1b402420", "$webhookUrl/export-webhook/result/2a1b402420", "POST", array(array("key", "value")))),
        new ExportCrawledVersion("$webhookUrl/export-webhook/crawled-version", "POST", array(array("key", "value")))
    );
    
    $exportedScanId = "1611042365";
    $copyleaks->export($authToken, $exportedScanId, $exportedScanId, $model);
    
    logInfo("-Export Example-");
}