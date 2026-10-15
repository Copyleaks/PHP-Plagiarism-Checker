<?php

namespace Copyleaks;

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
        return CopyleaksClientUtils::sendScanRequest($authToken, 'POST-JSON', '/v1/text-moderation/{scanId}/check', $scanId, $submission);
    }
 
}
