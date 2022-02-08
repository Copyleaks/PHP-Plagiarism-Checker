<?php

namespace Demo;

include_once('vendor/copyleaks/php-plagiarism-checker/autoload.php');
include_once('./demo.php');

use Demo\Test;

$USER_EMAIL = 'bayana@copyleaks.com'; // change this with your own copyleaks email.
$USER_KEY = '773b6b61-afb2-4465-a42f-a516c3c78409'; // change this with your own copyleaks API key.
$WEBHOOK_URL = '<WEBHOOK_URL>'; //exe https://glacial-refuge-96501.herokuapp.com/10b0z2w1

$test = new Test();
$test->run($USER_EMAIL, $USER_KEY, $WEBHOOK_URL);
