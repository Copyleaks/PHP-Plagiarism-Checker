<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;

function runCreditsBalanceExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $creditsBalance = $copyleaks->getCreditsBalance(/*authToken*/$authToken);
    
    logInfo(/*title*/"-Credits Balance Example-", /*info*/$creditsBalance);
}