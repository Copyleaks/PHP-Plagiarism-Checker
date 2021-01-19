<?php

namespace Demo;

include_once('../autoload.php');
include_once('./demo.php');

use Demo\Test;

$USER_EMAIL = 'copyleaks.com'; // change this with your own copyleaks email.
$USER_KEY = '<API_KEY>'; // change this with your own copyleaks API key.
$WEBHOOK_URL = '<WEBHOOK_URL>'; //exe https://glacial-refuge-96501.herokuapp.com/10b0z2w1

$test = new Test();
$test->run($USER_EMAIL, $USER_KEY, $WEBHOOK_URL);
