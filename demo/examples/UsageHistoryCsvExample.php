<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;

function runUsageHistoryCsvExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $usageHistoryCsv = $copyleaks->getUsagesHistoryCsv(/*authToken*/$authToken, /*startDate*/'01-01-2021', /*endDate*/'02-02-2021');
    
    logInfo(/*title*/"-Usage History CSV Example-", /*info*/$usageHistoryCsv);
}