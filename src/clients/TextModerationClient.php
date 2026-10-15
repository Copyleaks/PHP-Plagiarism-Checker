<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Text Moderation. Calls throw the exceptions listed on the Copyleaks class.
 */
class TextModerationClient{

    /**
     * Find potentially harmful content in a text. https://docs.copyleaks.com/reference/actions/text-moderation/check
     * @param CopyleaksAuthToken $authToken
     * @param string $scanId Your own scan ID.
     * @param CopyleaksTextModerationRequestModel $submission
     */
    public function submitText(CopyleaksAuthToken $authToken, string $scanId, CopyleaksTextModerationRequestModel $submission)
    {
        if (!isset($scanId)) {
            throw new InvalidArgumentException("Invalid scanId");
        }
        if (!isset($submission)) {
            throw new InvalidArgumentException("Invalid submission");
        }
  
        CopyleaksClientUtils::verifyAuthToken($authToken);
  
        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v1/text-moderation/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/check";

        return HttpClientService::Execute('POST-JSON', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
    }
 
}
