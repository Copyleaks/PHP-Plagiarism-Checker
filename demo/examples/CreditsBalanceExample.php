<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;

/**
 * Example demonstrating how to get credits balance
 */
function runCreditsBalanceExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $creditsBalance = $copyleaks->getCreditsBalance(/*authToken*/$authToken);
    
    logInfo(/*message*/"-Credits Balance Example-", /*context*/$creditsBalance);
}