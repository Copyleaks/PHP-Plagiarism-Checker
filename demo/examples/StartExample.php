<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksStartErrorHandlings;
use Copyleaks\CopyleaksStartRequestModel;

/**
 * Example demonstrating how to start scans
 */
function runStartExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $model = new CopyleaksStartRequestModel(array("cqcps25xxh5cloxe"), CopyleaksStartErrorHandlings::IGNORE);
    $start = $copyleaks->start($authToken, $model);
    
    logInfo("-Start Example-", $start);
}