<?php

namespace Copyleaks;

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
        return CopyleaksClientUtils::sendScanRequest($authToken, 'POST-JSON', '/v1/ai-video-detector/{scanId}/submit', $scanId, $submission);
    }
}
