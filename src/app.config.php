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
  private static $_API_SERVER_URI = 'https://api.copyleaks.com';
  private static $_IDENTITY_SERVER_URI = 'https://id.copyleaks.com';
  private static $_USER_AGENT = 'php-sdk/3.0';


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
}
