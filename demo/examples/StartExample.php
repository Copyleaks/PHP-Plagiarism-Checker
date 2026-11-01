<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksStartErrorHandlings;
use Copyleaks\CopyleaksStartRequestModel;

function runStartExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $model = new CopyleaksStartRequestModel(/*trigger*/array("cqcps25xxh5cloxe"), /*errorHandling*/CopyleaksStartErrorHandlings::IGNORE);
    $start = $copyleaks->start(/*authToken*/$authToken, /*data*/$model);
    
    logInfo(/*title*/"-Start Example-", /*info*/$start);
}