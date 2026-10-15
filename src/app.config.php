<?php

namespace Copyleaks;

class CopyleaksConfig
{
  /** Sent in the User-Agent header. */
  const SDK_VERSION = '5.3.0';

  /** Seconds to open a connection to a Copyleaks server. */
  const DEFAULT_CONNECT_TIMEOUT = 10;

  /** Seconds for a whole request: connecting, sending the body and reading the response. */
  const DEFAULT_TIMEOUT = 120;

  private static $_API_SERVER_URI = 'https://api.copyleaks.com';
  private static $_IDENTITY_SERVER_URI = 'https://id.copyleaks.com';
  private static $_USER_AGENT = 'php-sdk/' . self::SDK_VERSION;
  private static $_CONNECT_TIMEOUT = self::DEFAULT_CONNECT_TIMEOUT;
  private static $_TIMEOUT = self::DEFAULT_TIMEOUT;


  public static function SET_API_SERVER_URI($url)
  {
    self::$_API_SERVER_URI = $url;
  }

  public static function GET_API_SERVER_URI()
  {
    return self::$_API_SERVER_URI;
  }

  public static function SET_IDENTITY_SERVER_URI($url)
  {
    self::$_IDENTITY_SERVER_URI = $url;
  }
  public static function GET_IDENTITY_SERVER_URI()
  {
    return self::$_IDENTITY_SERVER_URI;
  }
  public static function GET_USER_AGENT()
  {
    return self::$_USER_AGENT;
  }

  /**
   * Time limit for opening a connection, for every request of this PHP process.
   * @param int|float $seconds Greater than 0. Default: DEFAULT_CONNECT_TIMEOUT.
   */
  public static function SET_CONNECT_TIMEOUT($seconds)
  {
    if (!is_numeric($seconds) || $seconds <= 0) {
      throw new \InvalidArgumentException('The connect timeout must be a number of seconds greater than 0.');
    }
    self::$_CONNECT_TIMEOUT = $seconds + 0;
  }

  /** @return int|float Seconds. */
  public static function GET_CONNECT_TIMEOUT()
  {
    return self::$_CONNECT_TIMEOUT;
  }

  /**
   * Time limit for a whole request, for every request of this PHP process. A longer request fails with a
   * CommandException. Raise it to upload very large files over a slow connection.
   * @param int|float $seconds Greater than 0, or 0 for no limit. Default: DEFAULT_TIMEOUT.
   */
  public static function SET_TIMEOUT($seconds)
  {
    if (!is_numeric($seconds) || $seconds < 0) {
      throw new \InvalidArgumentException('The timeout must be a number of seconds, or 0 for no limit.');
    }
    self::$_TIMEOUT = $seconds + 0;
  }

  /** @return int|float Seconds; 0 means no limit. */
  public static function GET_TIMEOUT()
  {
    return self::$_TIMEOUT;
  }
}
