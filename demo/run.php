<?php

namespace Demo;

// Run composer install, then from the demo folder start the webhook server with
// php -d xdebug.mode=off -S localhost:8080 webhookServer.php (expose it with ngrok http 8080 if needed)
// and, in a second terminal, run php run.php.
include_once('../autoload.php');
include_once('./demo.php');

use Demo\Test;

$USER_EMAIL = '<USER_EMAIL>'; // your Copyleaks account email
$USER_KEY = '<API_KEY>'; // your Copyleaks API key
$WEBHOOK_URL = '<WEBHOOK_URL>'; // your public webhook base URL

$test = new Test();
$test->run($USER_EMAIL, $USER_KEY, $WEBHOOK_URL);
