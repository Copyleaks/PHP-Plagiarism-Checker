<?php

namespace Demo\Examples;

function logInfo($title, $info = null): void
{
    echo "\n";
    echo "----------------" . $title . "----------------" . "\n\n";
    if ($info) {
        echo json_encode($info);
        echo "\n\n";
    }
}