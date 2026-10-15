<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Image Plagiarism Detector. Calls throw the exceptions listed on the Copyleaks class.
 */
class ImagePlagiarismClient
{
    /**
     * Search the web for full and partial copies of an image, with the pages where they were found.
     * https://docs.copyleaks.com/reference/actions/image-plagiarism-detector/check
     * @param CopyleaksAuthToken $authToken
     * @param string $scanId Your own scan ID, 3 to 36 characters.
     * @param CopyleaksImagePlagiarismRequestModel $submission
     * @return mixed Decoded JSON, see CopyleaksImagePlagiarismResponseModel::fromArray().
     * @throws InvalidArgumentException When the image cannot be read.
     */
    public function submit(CopyleaksAuthToken $authToken, string $scanId, CopyleaksImagePlagiarismRequestModel $submission)
    {
        if ($scanId === '') {
            throw new InvalidArgumentException("Invalid scanId");
        }

        CopyleaksClientUtils::verifyAuthToken($authToken);

        $url = CopyleaksConfig::GET_API_SERVER_URI() . "/v1/image-plagiarism-detector/" . CopyleaksClientUtils::encodePathSegment($scanId) . "/check";

        $temporaryFiles = [];
        try {
            $fields = $submission->toMultipartFields($temporaryFiles);
            return HttpClientService::Execute('MULTIPART', $url, CopyleaksClientUtils::authHeaders($authToken, false), $fields);
        } finally {
            foreach ($temporaryFiles as $path) {
                @unlink($path);
            }
        }
    }
}
