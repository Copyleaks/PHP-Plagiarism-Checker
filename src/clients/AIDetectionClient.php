<?php

namespace Copyleaks;

use InvalidArgumentException;
/**
 * AI Text Detection. Calls throw the exceptions listed on the Copyleaks class.
 */
class AIDetectionClient
{
    /**
     * Detect whether text was written by a human or by AI.
     * https://docs.copyleaks.com/reference/actions/writer-detector/check
     * @param CopyleaksAuthToken $authToken
     * @param string $scanId Your own scan ID.
     * @param CopyleaksNaturalLanguageSubmissionModel $submission
     */
    public function submitNaturalLanguage(CopyleaksAuthToken $authToken, string $scanId, CopyleaksNaturalLanguageSubmissionModel $submission)
    {
        if (!isset($scanId)) {
            throw new InvalidArgumentException("Invalid scanId");
        }
        if (!isset($submission)) {
            throw new InvalidArgumentException("Invalid submission");
        }

        CopyleaksClientUtils::verifyAuthToken($authToken);

        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v2/writer-detector/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/check";

        return HttpClientService::Execute('POST-JSON', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
    }

    /**
     * Get the AI Text Detection credit balance, as {"credits": 382}.
     * https://docs.copyleaks.com/reference/actions/writer-detector/credits
     * @param CopyleaksAuthToken $authToken
     * @return mixed Decoded JSON.
     */
    public function getCreditsBalance(CopyleaksAuthToken $authToken)
    {
        CopyleaksClientUtils::verifyAuthToken($authToken);

        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v2/writer-detector/credits";

        return HttpClientService::Execute('GET', $url, CopyleaksClientUtils::authHeaders($authToken, false));
    }
}
