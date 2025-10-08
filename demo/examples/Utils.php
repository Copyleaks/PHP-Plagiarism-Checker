<?php

namespace Demo\Examples;

/**
 * Shared utility function for logging information
 */
function logInfo($title, $info = null): void
{
    echo "\n";
    echo "----------------" . $title . "----------------" . "\n\n";
    if ($info) {
        echo json_encode($info);
        echo "\n\n";
    }
}