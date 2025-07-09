<?php

namespace Demo;

// open terminal and go to demo folder  run:  php -d xdebug.mode=off -S localhost:8080 webhookServer.php
// in a new terminal go to demo folder and run : php run.php
// if you are using ngrok expose you localhost: run ngrok http 8080
// include_once('../autoload.php');
// in order to be able to run the demo run composer install
include_once('./demo.php');

use Demo\Test;

$USER_EMAIL = '<USER_EMAIL>'; // change this with your own copyleaks email.
$USER_KEY = '<API_KEY>'; // change this with your own copyleaks API key.
$WEBHOOK_URL = '<WEBHOOK_URL>'; //exe https://glacial-refuge-96501.herokuapp.com/webhook

$test = new Test();
$test->run($USER_EMAIL, $USER_KEY, $WEBHOOK_URL);
