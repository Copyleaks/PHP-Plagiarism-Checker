<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksDeleteRequestModel;
use Copyleaks\IdObject;

/**
 * Example demonstrating how to delete scans
 */
function runDeleteExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $idsToDelete = array(new IdObject(/*id*/"cqcps25xxh5cloxe"));

    $model = new CopyleaksDeleteRequestModel(/*scans*/$idsToDelete, /*purge*/true, /*webhook*/"https://glacial-refuge-96501.herokuapp.com/18ml1by1/delete-hook");
    $delete = $copyleaks->delete(/*authToken*/$authToken, /*model*/$model);

    logInfo(/*message*/"-Delete Example-", /*context*/$delete);
}