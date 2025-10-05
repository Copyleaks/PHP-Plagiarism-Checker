<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;

/**
 * Example demonstrating how to get usage history CSV
 */
function runUsageHistoryCsvExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $usageHistoryCsv = $copyleaks->getUsagesHistoryCsv($authToken, '01-01-2021', '02-02-2021');
    
    logInfo("-Usage History CSV Example-", $usageHistoryCsv);
}