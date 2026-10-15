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

  /**
   * Sends an authenticated request for one scan to the API server.
   * @param string $verb See HttpClientService::Execute().
   * @param string $path With a {scanId} placeholder, e.g. '/v1/text-moderation/{scanId}/check'.
   * @param mixed $body Request body. A \Closure is called after the checks pass, to build it.
   * @param bool $json Send 'Content-Type: application/json'.
   * @return mixed Decoded JSON.
   * @throws InvalidArgumentException When $scanId is empty.
   * @throws AuthExipredException
   */
  public static function sendScanRequest(CopyleaksAuthToken $authToken, string $verb, string $path, string $scanId, $body = null, bool $json = true)
  {
    if ($scanId === '') {
      throw new InvalidArgumentException("Invalid scanId");
    }

    self::verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . str_replace('{scanId}', self::encodePathSegment($scanId), $path);
    if ($body instanceof \Closure) {
      $body = $body();
    }

    return HttpClientService::Execute($verb, $url, self::authHeaders($authToken, $json), $body);
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
