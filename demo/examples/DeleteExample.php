<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksDeleteRequestModel;
use Copyleaks\IdObject;

function runDeleteExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken, string $webhookUrl): void
{
    $idsToDelete = array(new IdObject(/*id*/"cqcps25xxh5cloxe"));

    $model = new CopyleaksDeleteRequestModel(/*scans*/$idsToDelete, /*purge*/true, /*completionWebhook*/"$webhookUrl/delete-hook");
    $delete = $copyleaks->delete(/*authToken*/$authToken, /*data*/$model);

    logInfo(/*title*/"-Delete Example-", /*info*/$delete);
}