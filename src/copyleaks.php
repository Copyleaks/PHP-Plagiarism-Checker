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
use Copyleaks\AIDetectionClient;
use Copyleaks\WritingAssistantClient;
use Copyleaks\TextModerationClient;
use InvalidArgumentException;

/**
 * The Copyleaks API client.
 *
 * Every method that calls the API can throw:
 * - CommandException: the server rejected the request (getStatusCode() returns the HTTP status), or no
 *   response was received, for example after a timeout (getStatusCode() returns 0).
 * - UnderMaintenanceException: Copyleaks servers are unavailable (503). Retry later with exponential backoff,
 *   see https://docs.copyleaks.com/concepts/performance/handling-failures
 * - RateLimitException: too many requests (429). Wait before calling again (see getRetryAfterSeconds()).
 * Methods that need a login token also throw AuthExipredException when the token has expired.
 */
class Copyleaks
{
  
  public AIDetectionClient $aiDetectionClient;
  public WritingAssistantClient $writingAssistantClient;
  public TextModerationClient $textModerationClient;

  public AiImagedetectionClient $aiImageDetectionClient;

  /**
   * Image Plagiarism Detector: searches the web for copies of an image.
   */
  public ImagePlagiarismClient $imagePlagiarismClient;

  /**
   * AI Video Detector: detects AI-generated video. Results are delivered to your webhook.
   */
  public AiVideoDetectionClient $aiVideoDetectionClient;

  public function __construct()
  {
      $this->aiDetectionClient = new AIDetectionClient();
      $this->writingAssistantClient = new WritingAssistantClient();
      $this->textModerationClient=new TextModerationClient();
      $this->aiImageDetectionClient = new AiImagedetectionClient();
      $this->imagePlagiarismClient = new ImagePlagiarismClient();
      $this->aiVideoDetectionClient = new AiVideoDetectionClient();
  }

  /**
   * Login to Copyleaks authentication server.
   * For more info: https://docs.copyleaks.com/reference/actions/account/login
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

    $headers = array(
      'Content-Type: application/json',
      'Accept: application/json',
      'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT()
    );

    $result = HttpClientService::Execute('POST-JSON', $url, $headers, $payload);

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
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/submit-file
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksFileSubmissionModel $submission Submission properties
   */

  public function submitFile(CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/submit/file/" . CopyleaksClientUtils::encodePathSegment($scanId);

    HttpClientService::Execute('PUT', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
  }
  
  /**
   * Starting a new process by providing a OCR image file to scan.
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/submit-ocr
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksFileOcrSubmissionModel $submission Submission properties
   */
  public function submitFileOcr(CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileOcrSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/submit/ocr/" . CopyleaksClientUtils::encodePathSegment($scanId);

    HttpClientService::Execute('PUT', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
  }

  /**
   * Starting a new process by providing a URL to scan.
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/submit-url
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Attach your own scan Id
   * @param CopyleaksURLSubmissionModel $submission Submission properties
   */
  public function submitUrl(CopyleaksAuthToken $authToken, string $scanId, CopyleaksURLSubmissionModel $submission)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }
    if (!isset($submission)) {
      throw new InvalidArgumentException("Invalid submission");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/submit/url/" . CopyleaksClientUtils::encodePathSegment($scanId);

    HttpClientService::Execute('PUT', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
  }

  /**
   * Exporting scans artifact into your server.
   * For more info: https://docs.copyleaks.com/reference/actions/downloads/export
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

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/downloads/" . CopyleaksClientUtils::encodePathSegment($scanId)
      . "/export/" . CopyleaksClientUtils::encodePathSegment($exportId);

    HttpClientService::Execute('POST-JSON', $url, CopyleaksClientUtils::authHeaders($authToken), $model);
  }

  /**
   * Start scanning all the files you submitted for a price-check.
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/start
   * @param CopyleaksAuthToken $authToken Your login token to Copyleaks server.
   * @param CopyleaksStartRequestModel $data Include information about which scans should be started.
   */
  public function start(CopyleaksAuthToken $authToken, CopyleaksStartRequestModel $data)
  {
    if (!isset($data)) {
      throw new InvalidArgumentException("Invalid Data");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/start";

    return HttpClientService::Execute('PATCH', $url, CopyleaksClientUtils::authHeaders($authToken), $data);
  }

  /**
   * Delete the specific process from the server.
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/delete
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param CopyleaksDeleteRequestModel $data
   */
  public function delete(CopyleaksAuthToken $authToken, CopyleaksDeleteRequestModel $data)
  {
    if (!isset($data)) {
      throw new InvalidArgumentException("Invalid Data");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3.1/scans/delete";

    $payload = RequestPayload::toPayload($data);
    // An empty completionWebhook means "no webhook": leave it out instead of sending an invalid URL.
    if (isset($payload->completionWebhook) && $payload->completionWebhook === '') {
      unset($payload->completionWebhook);
    }

    HttpClientService::Execute('PATCH', $url, CopyleaksClientUtils::authHeaders($authToken), $payload);
  }

  /**
   * Resend status webhooks for existing scans.
   * For more info: https://docs.copyleaks.com/reference/actions/authenticity/resend-webhook
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   * @param string $scanId Copyleaks scan Id
   */
  public function resendWebhook(CopyleaksAuthToken $authToken, string $scanId)
  {
    if (!isset($scanId)) {
      throw new InvalidArgumentException("Invalid scanId");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/webhooks/resend";

    HttpClientService::Execute('POST', $url, CopyleaksClientUtils::authHeaders($authToken));
  }

  /**
   * Get current credits balance for the Copyleaks account.
   * For more info: https://docs.copyleaks.com/reference/actions/admin/check-credits
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token
   */
  public function getCreditsBalance(CopyleaksAuthToken $authToken)
  {
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/credits";

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::authHeaders($authToken));
  }

  /**
   * This endpoint allows you to export your usage history between two dates.
   * The output results will be exported to a csv file and it will be attached to the response.
   * For more info: https://docs.copyleaks.com/reference/actions/admin/usage-history
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token.
   * @param string $startDate The start date to collect usage history from. Date Format: `dd-MM-yyyy`.
   * @param string $endDate The end date to collect usage history from. Date Format: `dd-MM-yyyy`.
   */
  public function getUsagesHistoryCsv(CopyleaksAuthToken $authToken, string $startDate, string $endDate)
  {
    if (!isset($startDate)) {
      throw new InvalidArgumentException("Invalid startDate");
    }

    if (!isset($endDate)) {
      throw new InvalidArgumentException("Invalid endDate");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/usages/history?"
      . http_build_query(['start' => $startDate, 'end' => $endDate], '', '&', PHP_QUERY_RFC3986);

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::authHeaders($authToken, false));
  }

  /**
   * Get a list of the supported languages for OCR (this is not a list of supported languages for the api, but only for the OCR files scan).
   * No login needed.
   * For more info: https://docs.copyleaks.com/reference/actions/miscellaneous/ocr-supported-languages
   * @return array List of supported OCR languages.
   */
  public function getOCRSupportedLanguages()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/ocr-languages-list';

    return (array)HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Get a list of the file types supported by the Plagiarism Checker.
   * No login needed.
   * For more info: https://docs.copyleaks.com/reference/actions/miscellaneous/supported-plagiarism-file-types
   * @return mixed List of supported file types.
   */
  public function getSupportedFileTypes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/supported-plagiarism-file-types';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Get the file types supported by AI Text Detection, as {"supportedAiFileTypes": ["pdf", "docx", ...]}.
   * No login needed.
   * For more info: https://docs.copyleaks.com/reference/actions/miscellaneous/supported-ai-text-detection-file-types
   * @return mixed The decoded JSON response.
   */
  public function getSupportedAiTextDetectionFileTypes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/supported-ai-text-detection-file-types';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Get the languages supported by cross-language plagiarism detection, as
   * {"documentLanguages": {"danish": "da", ...}, "resultLanguages": {"french": "fr", ...}}: objects that map each
   * language name to its ISO-639-1 code. Get the codes with array_values((array) $result->resultLanguages).
   * Use a resultLanguages code in SubmissionScanning's crossLanguages.
   * No login needed.
   * For more info: https://docs.copyleaks.com/reference/actions/miscellaneous/supported-cross-languages
   * @return mixed The decoded JSON response.
   */
  public function getAllowedCrossLanguages()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/allowed-cross-languages';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Get the information of a Private Cloud Hub repository: name, status, credits, masking policy and more.
   * Requires the "Super Admin" or "Admin" role.
   * For more info: https://docs.copyleaks.com/reference/actions/private-cloud-hub/info
   * @param CopyleaksAuthToken $authToken Copyleaks authentication token.
   * @param string $repositoryId The repository ID, from the Copyleaks Admin Dashboard.
   * @return mixed The decoded JSON response.
   */
  public function getRepositoryInfo(CopyleaksAuthToken $authToken, string $repositoryId)
  {
    if ($repositoryId === '') {
      throw new InvalidArgumentException("Invalid repositoryId");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/repositories/repository/'
      . CopyleaksClientUtils::encodePathSegment($repositoryId) . '/info';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::authHeaders($authToken, false));
  }

  /**
   * Get updates about copyleaks api release notes.
   * No login needed.
   * For more info: https://docs.copyleaks.com/resources/updates/release-notes
   * @return mixed List of release notes.
   */
  public function getReleaseNotes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/release-logs.json';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }
}
