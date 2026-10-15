<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Grammar Checker (Writing Assistant). Calls throw the exceptions listed on the Copyleaks class.
 */
class WritingAssistantClient{

    /**
     * Get grammar, spelling and sentence corrections for a text.
     * https://docs.copyleaks.com/reference/actions/writing-assistant/check
     * @param CopyleaksAuthToken $authToken
     * @param string $scanId Your own scan ID.
     * @param CopyleaksWritingAssistantSubmissionModel $submission
     */
    public function submitText(CopyleaksAuthToken $authToken, string $scanId, CopyleaksWritingAssistantSubmissionModel $submission)
    {
        if (!isset($scanId)) {
            throw new InvalidArgumentException("Invalid scanId");
        }
        if (!isset($submission)) {
            throw new InvalidArgumentException("Invalid submission");
        }
  
        CopyleaksClientUtils::verifyAuthToken($authToken);
  
        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v1/writing-feedback/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/check";

        return HttpClientService::Execute('POST-JSON', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
    }
  

    /**
     * Correction types of the Writing Assistant (the same for all languages). No login needed.
     * https://docs.copyleaks.com/reference/actions/writing-assistant/correction-types
     * @param string $languageCode ISO 639-1 language of the returned texts. Supported: 'en'.
     */
    public function getCorrectionTypes(string $languageCode)
    {
      $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v1/writing-feedback/correction-types/" . CopyleaksClientUtils::encodePathSegment($languageCode);

      return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::publicHeaders());
    }
}
