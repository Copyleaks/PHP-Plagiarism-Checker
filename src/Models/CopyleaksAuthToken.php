<?php

namespace Copyleaks;

class CopyleaksAuthToken
{
  public string $expires;
  public string $accessToken;
  public string $issued;
  public function __construct(string $expires, string $accessToken, string $issued)
  {
    $this->expires = $expires;
    $this->accessToken = $accessToken;
    $this->issued = $issued;
  }
}
