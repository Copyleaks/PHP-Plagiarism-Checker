<?php

namespace Demo;

// include_once('vendor/copyleaks/php-plagiarism-checker/autoload.php');
include_once('../autoload.php');
include_once('./demo.php');

use Demo\Test;

$USER_EMAIL = 'bayana@copyleaks.com'; // change this with your own copyleaks email.
$USER_KEY = '50625ec7-db62-436b-ac56-c6a242a94343'; // change this with your own copyleaks API key.
$WEBHOOK_URL = '<WEBHOOK_URL>'; //exe https://glacial-refuge-96501.herokuapp.com/10b0z2w1

$test = new Test();
$test->run($USER_EMAIL, $USER_KEY, $WEBHOOK_URL);
