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

class TextModerationClient{

    /**
     * Use Copyleaks Text Moderation to identify potentially harmful content across multiple categories.
     * For more info: https://docs.copyleaks.com/reference/actions/text-moderation/check
     *
     * Exceptions: see the Copyleaks class.
     *
     * @param authToken  Copyleaks authentication token
     * @param scanId     Attach your own scan Id
     * @param submission Submission model
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
