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
    $idsToDelete = array(new IdObject("cqcps25xxh5cloxe"));

    $model = new CopyleaksDeleteRequestModel($idsToDelete, true, "https://glacial-refuge-96501.herokuapp.com/18ml1by1/delete-hook");
    $delete = $copyleaks->delete($authToken, $model);

    logInfo("-Delete Example-", $delete);
}