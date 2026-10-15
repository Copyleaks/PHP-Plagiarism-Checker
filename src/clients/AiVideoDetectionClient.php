<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * AI Video Detection. Calls throw the exceptions listed on the Copyleaks class.
 */
class AiVideoDetectionClient
{
    /**
     * Submit a video URL for AI detection. Answers 201 right away; the result is sent to
     * $submission->webhooks->url, read it with CopyleaksAiVideoDetectionResponseModel::fromArray().
     * https://docs.copyleaks.com/reference/actions/ai-video-detector/submit
     * @param CopyleaksAuthToken $authToken
     * @param string $scanId Your own scan ID, 3 to 36 characters.
     * @param CopyleaksAiVideoDetectionRequestModel $submission
     * @return mixed Decoded JSON (an empty object).
     */
    public function submit(CopyleaksAuthToken $authToken, string $scanId, CopyleaksAiVideoDetectionRequestModel $submission)
    {
        if ($scanId === '') {
            throw new InvalidArgumentException("Invalid scanId");
        }

        CopyleaksClientUtils::verifyAuthToken($authToken);

        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v1/ai-video-detector/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/submit";

        return HttpClientService::Execute('POST-JSON', $url, CopyleaksClientUtils::authHeaders($authToken), $submission);
    }
}
