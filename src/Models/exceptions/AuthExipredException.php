<?php

namespace Copyleaks;

use Exception;

class AuthExipredException extends Exception
{
  public function __construct($code = 0, ?Exception $previous = null)
  {
    parent::__construct("Authentication Expired. Need to login again.", $code, $previous);
  }
}
