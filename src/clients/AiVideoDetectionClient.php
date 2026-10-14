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

use InvalidArgumentException;

class AiVideoDetectionClient
{
    /**
     * Submits the URL of a video for AI detection. The endpoint answers 201 Created right away, and the
     * result is sent to $submission->webhooks->url when processing is complete. Read the webhook body with
     * CopyleaksAiVideoDetectionResponseModel::fromArray().
     * For more info: https://docs.copyleaks.com/reference/actions/ai-video-detector/submit
     *
     * Exceptions: see the Copyleaks class.
     *
     * @param CopyleaksAuthToken $authToken Copyleaks authentication token
     * @param string $scanId Attach your own scan Id (3 to 36 characters)
     * @param CopyleaksAiVideoDetectionRequestModel $submission The video URL, the webhook and the options
     * @return mixed The decoded JSON response (an empty object).
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
