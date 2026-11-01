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
     * @throws InvalidArgumentException When scanId is empty or the image cannot be read.
     */
    public function submit(CopyleaksAuthToken $authToken, string $scanId, CopyleaksImagePlagiarismRequestModel $submission)
    {
        $temporaryFiles = [];
        try {
            $fields = static function () use ($submission, &$temporaryFiles): array {
                return $submission->toMultipartFields($temporaryFiles);
            };
            return CopyleaksClientUtils::sendScanRequest($authToken, 'MULTIPART', '/v1/image-plagiarism-detector/{scanId}/check', $scanId, $fields, false);
        } finally {
            foreach ($temporaryFiles as $path) {
                @unlink($path);
            }
        }
    }
}
