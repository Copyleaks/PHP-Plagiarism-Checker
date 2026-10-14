<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

class CopyleaksConfig
{
  /**
   * The version of this SDK, sent in the User-Agent header.
   */
  const SDK_VERSION = '5.3.0';

  /**
   * Default time limit, in seconds, for opening the connection to a Copyleaks server.
   */
  const DEFAULT_CONNECT_TIMEOUT = 10;

  /**
   * Default time limit, in seconds, for a whole request: connecting, sending the body and reading the response.
   */
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
   * Sets the time limit for opening the connection to a Copyleaks server.
   * Applies to every request sent by this SDK in the current PHP process.
   * @param int|float $seconds Seconds, greater than 0. Default: 10.
   */
  public static function SET_CONNECT_TIMEOUT($seconds)
  {
    if (!is_numeric($seconds) || $seconds <= 0) {
      throw new \InvalidArgumentException('The connect timeout must be a number of seconds greater than 0.');
    }
    self::$_CONNECT_TIMEOUT = $seconds + 0;
  }

  /**
   * @return int|float The time limit, in seconds, for opening the connection to a Copyleaks server.
   */
  public static function GET_CONNECT_TIMEOUT()
  {
    return self::$_CONNECT_TIMEOUT;
  }

  /**
   * Sets the time limit for a whole request: connecting, sending the body and reading the response.
   * A request that takes longer fails with a CommandException.
   * Applies to every request sent by this SDK in the current PHP process.
   * Raise it if you upload very large files over a slow connection.
   * @param int|float $seconds Seconds, greater than 0, or 0 for no limit. Default: 120.
   */
  public static function SET_TIMEOUT($seconds)
  {
    if (!is_numeric($seconds) || $seconds < 0) {
      throw new \InvalidArgumentException('The timeout must be a number of seconds, or 0 for no limit.');
    }
    self::$_TIMEOUT = $seconds + 0;
  }

  /**
   * @return int|float The time limit, in seconds, for a whole request. 0 means no limit.
   */
  public static function GET_TIMEOUT()
  {
    return self::$_TIMEOUT;
  }
}
