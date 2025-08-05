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
use Copyleaks\DeprecationService;
class AIDetectionClient
{
    /**
     * Use Copyleaks AI Content Detection to differentiate between human texts and AI written texts.
     * 
     * * Exceptions:
     * * CommandExceptions: Server reject the request. See response status code,
     * headers and content for more info.
     * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
     * We recommend to implement exponential backoff algorithm as described here:
     * https://api.copyleaks.com/documentation/v3/exponential-backoff
     *
     * @param authToken  Copyleaks authentication token
     * @param scanId     Attach your own scan Id
     * @param submission Submission model
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

        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v2/writer-detector/$scanId/check";
        $authorization = "Authorization: Bearer " . $authToken->accessToken;
        $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

        ObjectFilter::filterNullProperties($submission);

        return HttpClientService::Execute('POST-JSON', $url, $headers, $submission);
    }

    /**
     * Use Copyleaks AI Content Detection to differentiate between human source code and AI written source code.
     * 
     * * Exceptions:
     * * CommandExceptions: Server reject the request. See response status code,
     * headers and content for more info.
     * * UnderMaintenanceException: Copyleaks servers are unavailable for maintenance.
     * We recommend to implement exponential backoff algorithm as described here:
     * https://api.copyleaks.com/documentation/v3/exponential-backoff
     *
     * @param authToken  Copyleaks authentication token
     * @param scanId     Attach your own scan Id
     * @param submission Submission model
     */
    public function submitSourceCode(CopyleaksAuthToken $authToken, string $scanId, CopyleaksSourceCodeSubmissionModel $submission)
    {
        if (!isset($scanId)) {
            throw new InvalidArgumentException("Invalid scanId");
        }
        if (!isset($submission)) {
            throw new InvalidArgumentException("Invalid submission");
        }

        CopyleaksClientUtils::verifyAuthToken($authToken);
        DeprecationService::showDeprecationMessage();
        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v2/writer-detector/source-code/$scanId/check";
        $authorization = "Authorization: Bearer " . $authToken->accessToken;
        $headers = array('Content-Type: application/json', 'User-Agent: ' . CopyleaksConfig::GET_USER_AGENT(), $authorization);

        ObjectFilter::filterNullProperties($submission);

        return HttpClientService::Execute('POST-JSON', $url, $headers, $submission);
    }
}
