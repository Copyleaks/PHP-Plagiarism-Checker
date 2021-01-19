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

use Copyleaks\CopyleaksConfig;
use Copyleaks\HttpClientService;
use InvalidArgumentException;

class Copyleaks
{
  /**
   * Login to Copyleaks authentication server.
   * For more info: https://api.copyleaks.com/documentation/v3/account/login.
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *    headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param String $email Copyleaks account email address.
   * @param String $key Copyleaks account secret key.
   * @return CopyleaksAuthToken A authentication token that being expired after certain amount of time.
   */
  public function login(string $email, string $key): CopyleaksAuthToken
  {
    if (!isset($email)) {
      throw new InvalidArgumentException("Invalid Email");
    }
    if (!isset($key)) {
      throw new InvalidArgumentException("Invalid key");
    }

    $url = CopyleaksConfig::GET_IDENTITY_SERVER_URI() . '/v3/account/login/api';

    $payload = (array)[
      'email' => $email,
      'key' => $key
    ];

    $headers = (array)[
      'Content-Type' => 'application/json',
      'User-Agent' => CopyleaksConfig::GET_USER_AGENT()
    ];

    $result = HttpClientService::Execute('POST', $url, $headers, $payload);

    return new CopyleaksAuthToken($result->{'.expires'}, $result->{'access_token'}, $result->{'.issued'});
  }

  /**
   * Verify that Copyleaks authentication token is exists and not exipired.
   * * Exceptions:
   * * * AuthExipredException: authentication expired. Need to login again.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token   
   */
  public function verifyAuthToken(CopyleaksAuthToken $authToken)
  {
    if (!isset($authToken)) {
      throw new InvalidArgumentException("authToken is required");
    }

    $date =  strtotime('+ 5 minutes'); // adds 5 minutes ahead for a safety shield.
    $expiresDate = strtotime($authToken->expires);
    if ($expiresDate <= $date) {
      throw new AuthExipredException(); // expired
    }
  }

  /**
   * Starting a new process by providing a file to scan.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/submit/file
   * https://api.copyleaks.com/documentation/v3/businesses/submit/file
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksFileSubmissionModel $submission Submission properties
   */
  public function submitFile(string $product, CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/submit/file/$scanId";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('PUT', $url, $headers, $submission);
  }

  /**
   * Starting a new process by providing a OCR image file to scan.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/submit/ocr
   * https://api.copyleaks.com/documentation/v3/businesses/submit/ocr
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksFileOcrSubmissionModel $submission Submission properties
   */
  public function submitFileOcr(string $product, CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileOcrSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/submit/ocr/$scanId";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('PUT', $url, $headers, $submission);
  }

  /**
   * Starting a new process by providing a URL to scan.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/submit/url
   * https://api.copyleaks.com/documentation/v3/businesses/submit/url
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksURLSubmissionModel $submission Submission properties
   */
  public function submitUrl(string $product, CopyleaksAuthToken $authToken, string $scanId, CopyleaksURLSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/submit/url/$scanId";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('PUT', $url, $headers, $submission);
  }

  /**
   * Exporting scans artifact into your server.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/downloads/export
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param CopyleaksAuthToken $authToken Your login token to Copyleaks server
   * @param string $scanId The scan ID of the specific scan to export.
   * @param string $exportId A new Id for the export process.
   * @param CopyleaksExportModel $model Request of which artifact should be exported.
   */
  public function export(CopyleaksAuthToken $authToken, string $scanId, string $exportId, CopyleaksExportModel $model)
  {
    if (!isset($authToken)) {
      throw new InvalidArgumentException("Invalid authToken");
    }
    if (!isset($exportId)) {
      throw new InvalidArgumentException("Invalid exportId");
    }
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($model)) {
      throw new InvalidArgumentException("Invalid model");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/downloads/${scanId}/export/${exportId}";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('POST-JSON', $url, $headers, $model);
  }

  /**
   * Start scanning all the files you submitted for a price-check.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/start
   * https://api.copyleaks.com/documentation/v3/businesses/start
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Your login token to Copyleaks server.
   * @param CopyleaksStartRequestModel $data Include information about which scans should be started.
   */
  public function start(string $product, CopyleaksAuthToken $authToken, CopyleaksStartRequestModel $data)
  {
    if (!isset($data)) {
      throw new InvalidArgumentException("Invalid Data");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/${product}/start";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('PATCH', $url, $headers, $data);
  }

  /**
   * Delete the specific process from the server.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/delete
   * https://api.copyleaks.com/documentation/v3/businesses/delete
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param CopyleaksDeleteRequestModel $data
   */
  public function delete(string $product, CopyleaksAuthToken $authToken, CopyleaksDeleteRequestModel $data)
  {
    if (!isset($data)) {
      throw new InvalidArgumentException("Invalid Data");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3.1/${product}/delete";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('PATCH', $url, $headers, $data);
  }

  /**
   * Resend status webhooks for existing scans.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/webhook-resend
   * https://api.copyleaks.com/documentation/v3/businesses/webhook-resend
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Copyleaks scan Id
   */
  public function resendWebhook(string $product, CopyleaksAuthToken $authToken, string $scanId)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/scans/$scanId/webhooks/resend";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    HttpClientService::Execute('POST', $url, $headers);
  }

  /**
   * Get current credits balance for the Copyleaks account.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/credits
   * https://api.copyleaks.com/documentation/v3/businesses/credits
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * * * RateLimitException: Too many requests. Please wait before calling again.
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   */
  public function getCreditsBalance(string $product, CopyleaksAuthToken $authToken)
  {
    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/credits";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    return HttpClientService::Execute('GET', $url, $headers);
  }

  /**
   * This endpoint allows you to export your usage history between two dates.
   * The output results will be exported to a csv file and it will be attached to the response.
   * For more info:
   * https://api.copyleaks.com/documentation/v3/education/usages/history
   * https://api.copyleaks.com/documentation/v3/businesses/usages/history
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * * * RateLimitException: Too many requests. Please wait before calling again.
   * @param string $product Which product (education or businesses) is being use.
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token.
   * @param string $startDate The start date to collect usage history from. Date Format: `dd-MM-yyyy`.
   * @param string $endDate The end date to collect usage history from. Date Format: `dd-MM-yyyy`.
   */
  public function getUsagesHistoryCsv(string $product, CopyleaksAuthToken $authToken, string $startDate, string $endDate)
  {
    if (!isset($startDate)) {
      throw new InvalidArgumentException("Invalid startDate");
    }

    if (!isset($endDate)) {
      throw new InvalidArgumentException("Invalid endDate");
    }

    $this->validateProduct($product);
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/$product/usages/history?start=$startDate&end=$endDate";

    $authorization = "Authorization: Bearer $authToken->accessToken";

    $headers = array('User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

    return HttpClientService::Execute('GET', $url, $headers);
  }

  /**
   * Get a list of the supported languages for OCR (this is not a list of supported languages for the api, but only for the OCR files scan).
   * For more info: https://api.copyleaks.com/documentation/v3/specifications/ocr-languages/list
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * * * RateLimitException: Too many requests. Please wait before calling again.
   * @return array List of supported OCR languages.
   */
  public function getOCRSupportedLanguages()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/ocr-languages-list';

    $headers = (array)[
      'Content-Type' => 'application/json',
      'User-Agent' => CopyleaksConfig::GET_USER_AGENT()
    ];

    return (array)HttpClientService::Execute('GET', $url, $headers);
  }

  /**
   * Get a list of the supported file types.
   * For more info: https://api.copyleaks.com/documentation/v3/specifications/supported-file-types
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * * * RateLimitException: Too many requests. Please wait before calling again.
   * @return mixed List of supported file types.
   */
  public function getSupportedFileTypes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/supported-file-types';
    $headers = (array)[
      'Content-Type' => 'application/json',
      'User-Agent' => CopyleaksConfig::GET_USER_AGENT()
    ];

    return HttpClientService::Execute('GET', $url, $headers);
  }

  /**
   * Get updates about copyleaks api release notes.
   * For more info: https://api.copyleaks.com/documentation/v3/release-notes
   * * Exceptions:
   * * * CommandExceptions: Server reject the request. See response status code,
   *     headers and content for more info.
   * * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
   *     We recommend to implement exponential backoff algorithm as described here:
   *     https://api.copyleaks.com/documentation/v3/exponential-backoff
   * * * RateLimitException: Too many requests. Please wait before calling again.
   * @return mixed List of release notes.
   */
  public function getReleaseNotes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/release-logs.json';

    $headers = (array)[
      'Content-Type' => 'application/json',
      'User-Agent' => CopyleaksConfig::GET_USER_AGENT()
    ];
    return HttpClientService::Execute('GET', $url, $headers);
  }

  private function validateProduct(string $product)
  {
    if (!isset($product) || ($product != 'education' && $product != 'businesses')) {
      throw new InvalidArgumentException("Invalid product, product must be set to 'education' or 'businesses'");
    }
  }
}
