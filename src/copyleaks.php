<?php

namespace Copyleaks;

use Copyleaks\CopyleaksConfig;
use Copyleaks\HttpClientService;
use Copyleaks\AIDetectionClient;
use Copyleaks\WritingAssistantClient;
use Copyleaks\TextModerationClient;
use InvalidArgumentException;

/**
 * The Copyleaks API client. API calls can throw:
 * - CommandException: request rejected (getStatusCode() is the HTTP status) or no response, e.g. a timeout (0).
 * - UnderMaintenanceException: 503. Retry later with exponential backoff,
 *   see https://docs.copyleaks.com/concepts/performance/handling-failures
 * - RateLimitException: 429. Wait getRetryAfterSeconds() before calling again.
 * - AuthExipredException: the login token has expired (calls that take a token).
 */
class Copyleaks
{
  
  public AIDetectionClient $aiDetectionClient;
  public WritingAssistantClient $writingAssistantClient;
  public TextModerationClient $textModerationClient;

  public AiImagedetectionClient $aiImageDetectionClient;

  /** Searches the web for copies of an image. */
  public ImagePlagiarismClient $imagePlagiarismClient;

  /** Results are delivered to your webhook. */
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
   * Log in. https://docs.copyleaks.com/reference/actions/account/login
   * @param String $email
   * @param String $key Account API key.
   * @return CopyleaksAuthToken Valid until its expires time.
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
   * @param CopyleaksAuthToken $authToken
   * @throws AuthExipredException The token has expired or expires within 5 minutes; log in again.
   */
  public function verifyAuthToken(CopyleaksAuthToken $authToken)
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

  /**
   * Submit a file to scan. https://docs.copyleaks.com/reference/actions/authenticity/submit-file
   * @param CopyleaksAuthToken $authToken
   * @param string $scanId Your own scan ID.
   * @param CopyleaksFileSubmissionModel $submission
   */
  public function submitFile(CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileSubmissionModel $submission)
  {
    if ($scanId === '') {
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
   * Submit an image to scan its text with OCR. https://docs.copyleaks.com/reference/actions/authenticity/submit-ocr
   * @param CopyleaksAuthToken $authToken
   * @param string $scanId Your own scan ID.
   * @param CopyleaksFileOcrSubmissionModel $submission
   */
  public function submitFileOcr(CopyleaksAuthToken $authToken, string $scanId, CopyleaksFileOcrSubmissionModel $submission)
  {
    if ($scanId === '') {
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
   * Submit a URL to scan. https://docs.copyleaks.com/reference/actions/authenticity/submit-url
   * @param CopyleaksAuthToken $authToken
   * @param string $scanId Your own scan ID.
   * @param CopyleaksURLSubmissionModel $submission
   */
  public function submitUrl(CopyleaksAuthToken $authToken, string $scanId, CopyleaksURLSubmissionModel $submission)
  {
    if ($scanId === '') {
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
   * Export scan artifacts to your server. https://docs.copyleaks.com/reference/actions/downloads/export
   * @param CopyleaksAuthToken $authToken
   * @param string $scanId
   * @param string $exportId A new ID for this export.
   * @param CopyleaksExportModel $model Which artifacts to export, and where.
   */
  public function export(CopyleaksAuthToken $authToken, string $scanId, string $exportId, CopyleaksExportModel $model)
  {
    if (!isset($authToken)) {
      throw new InvalidArgumentException("Invalid authToken");
    }
    if ($exportId === '') {
      throw new InvalidArgumentException("Invalid exportId");
    }
    if ($scanId === '') {
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
   * Start scans submitted for a price check. https://docs.copyleaks.com/reference/actions/authenticity/start
   * @param CopyleaksAuthToken $authToken
   * @param CopyleaksStartRequestModel $data Which scans to start.
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
   * Delete scans. https://docs.copyleaks.com/reference/actions/authenticity/delete
   * @param CopyleaksAuthToken $authToken
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
   * Resend the status webhooks of a scan. https://docs.copyleaks.com/reference/actions/authenticity/resend-webhook
   * @param CopyleaksAuthToken $authToken
   * @param string $scanId
   */
  public function resendWebhook(CopyleaksAuthToken $authToken, string $scanId)
  {
    if ($scanId === '') {
      throw new InvalidArgumentException("Invalid scanId");
    }

    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/webhooks/resend";

    HttpClientService::Execute('POST', $url, CopyleaksClientUtils::authHeaders($authToken));
  }

  /**
   * Get the account's credit balance. https://docs.copyleaks.com/reference/actions/admin/check-credits
   * @param CopyleaksAuthToken $authToken
   */
  public function getCreditsBalance(CopyleaksAuthToken $authToken)
  {
    $this->verifyAuthToken($authToken);

    $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v3/scans/credits";

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::authHeaders($authToken));
  }

  /**
   * Get the usage history between two dates as CSV. https://docs.copyleaks.com/reference/actions/admin/usage-history
   * @param CopyleaksAuthToken $authToken
   * @param string $startDate `dd-MM-yyyy`.
   * @param string $endDate `dd-MM-yyyy`.
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
   * Languages supported by OCR scans (not by the API in general). No login needed.
   * https://docs.copyleaks.com/reference/actions/miscellaneous/ocr-supported-languages
   * @return array
   */
  public function getOCRSupportedLanguages()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/ocr-languages-list';

    return (array)HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * File types supported by the Plagiarism Checker. No login needed.
   * https://docs.copyleaks.com/reference/actions/miscellaneous/supported-plagiarism-file-types
   * @return mixed
   */
  public function getSupportedFileTypes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/supported-plagiarism-file-types';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * File types supported by AI Text Detection, as {"supportedAiFileTypes": ["pdf", "docx", ...]}. No login needed.
   * https://docs.copyleaks.com/reference/actions/miscellaneous/supported-ai-text-detection-file-types
   * @return mixed Decoded JSON.
   */
  public function getSupportedAiTextDetectionFileTypes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/supported-ai-text-detection-file-types';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Cross-language detection languages, as {"documentLanguages": {"danish": "da", ...}, "resultLanguages": {...}}
   * (name to ISO-639-1 code). Pass resultLanguages codes, array_values((array) $result->resultLanguages), to
   * SubmissionScanning's crossLanguages. No login needed.
   * https://docs.copyleaks.com/reference/actions/miscellaneous/supported-cross-languages
   * @return mixed Decoded JSON.
   */
  public function getAllowedCrossLanguages()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/miscellaneous/allowed-cross-languages';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }

  /**
   * Get a Private Cloud Hub repository's name, status, credits, masking policy and more. Needs the Admin or
   * Super Admin role. https://docs.copyleaks.com/reference/actions/private-cloud-hub/info
   * @param CopyleaksAuthToken $authToken
   * @param string $repositoryId From the Copyleaks Admin Dashboard.
   * @return mixed Decoded JSON.
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
   * API release notes. No login needed. https://docs.copyleaks.com/resources/updates/release-notes
   * @return mixed
   */
  public function getReleaseNotes()
  {
    $url = CopyleaksConfig::GET_API_SERVER_URI() . '/v3/release-logs.json';

    return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
  }
}
