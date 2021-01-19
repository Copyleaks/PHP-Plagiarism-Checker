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

use InvalidArgumentException;

class HttpClientService
{
  public static function Execute(string $verb, string $url, array $headers, $data = null)
  {
    //open connection
    $ch = curl_init();

    //set the url
    curl_setopt($ch, CURLOPT_URL, $url);

    switch ($verb) {
      case 'POST':
      case 'POST-JSON':
        curl_setopt($ch, CURLOPT_POST, true);
        break;
      case 'PATCH':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PATCH');
        break;
      case 'PUT':
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        break;
      case 'GET':
        break;
      default:
        throw new InvalidArgumentException('Invalid $verp');
    }


    if (isset($data)) {
      //url-ify the data.
      if ($verb == "PATCH" || $verb == "PUT" || $verb == "POST-JSON") {
        $fields_string = json_encode($data);
        //remove null properties
        $fields_string = preg_replace('/,\s*"[^"]+":null|"[^"]+":null,?/', '', $fields_string);
      } else {
        $fields_string = http_build_query($data);
      }
      // echo $fields_string . "\n";
      curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
    }

    if (isset($headers)) {
      // setup headers
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    // setup response at curl_exec
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    //execute post
    $result = curl_exec($ch);

    // get status code
    $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if (isSuccessStatusCode($statusCode)) {
      if (isset($result)) {
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        if ($contentType == 'application/json; charset=utf-8') {
          curl_close($ch);
          return json_decode($result);
        } else {
          curl_close($ch);
          return $result;
        }
      } else {
        curl_close($ch);
        return;
      }
    } elseif (isUnderMaintenanceResponse($statusCode)) {
      //close connection
      curl_close($ch);
      throw new UnderMaintenanceException();
    } elseif (isRateLimitResponse($statusCode)) {
      //close connection
      curl_close($ch);
      throw new RateLimitException();
    } else {
      $err_message = "\n" . "----Copyleaks SDK Error-----" . "\n\n";
      $curl_info = curl_getinfo($ch);
      $curl_err = curl_error($ch);
      if (isset($result) && $result != "") {
        $err_message = $err_message . "result:" . "\n" . json_encode($result) . "\n\n";
      }
      if (isset($curl_info)) {
        $err_message = $err_message . "info:" . "\n" .  json_encode($curl_info) . "\n\n";
      }
      if (isset($curl_err) && $curl_err != "") {
        $err_message = $err_message . "error:" . "\n" . json_encode($curl_err) . "\n\n";
      }
      $err_message = $err_message . "----------------------------\n";
      throw new CommandException($err_message);
    }
  }
}
