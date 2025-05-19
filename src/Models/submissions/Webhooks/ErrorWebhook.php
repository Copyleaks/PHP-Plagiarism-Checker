<?php


namespace Copyleaks;
use Copyleaks\StatusWebhook;
use Copyleaks\Error;

class ErrorWebhook extends StatusWebhook{

    public ?Error $error;

    public function __construct(
        ?Error $error = null
    ) {
        $this->error = $error;
    }
}