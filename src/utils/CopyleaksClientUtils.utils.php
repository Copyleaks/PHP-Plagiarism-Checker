<?php

namespace Copyleaks;

use InvalidArgumentException;

class CopyleaksClientUtils
{
  /** Percent-encodes one URL path segment, such as a scan ID. */
  public static function encodePathSegment(string $value): string
  {
    return rawurlencode($value);
  }

  /** @return string[] */
  public static function authHeaders(CopyleaksAuthToken $authToken, bool $json = true): array
  {
    $headers = [];
    if ($json) {
      $headers[] = 'Content-Type: application/json';
    }
    $headers[] = 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT();
    $headers[] = 'Authorization: Bearer ' . $authToken->accessToken;
    return $headers;
  }

  /**
   * For requests that need no login.
   * @return string[]
   */
  public static function publicHeaders(): array
  {
    return ['User-Agent: ' . CopyleaksConfig::GET_USER_AGENT()];
  }

  public static function verifyAuthToken(CopyleaksAuthToken $authToken)
  {
    if (!isset($authToken)) {
      throw new InvalidArgumentException("authToken is required");
    }

    $date =  strtotime('+ 5 minutes'); // safety margin
    $expiresDate = strtotime($authToken->expires);
    if ($expiresDate <= $date) {
      throw new AuthExipredException();
    }
  }
}
