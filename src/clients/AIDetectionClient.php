<?php

namespace Copyleaks;

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
        return CopyleaksClientUtils::sendScanRequest($authToken, 'POST-JSON', '/v2/writer-detector/{scanId}/check', $scanId, $submission);
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
