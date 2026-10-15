<?php

namespace Copyleaks;

/**
 * Image Plagiarism Detector result: fromArray(json_decode(json_encode($response), true)).
 * See https://docs.copyleaks.com/reference/data-types/authenticity/results/image-plagiarism-response
 */
class CopyleaksImagePlagiarismResponseModel
{
    /** The developerPayload you sent, or null. */
    public ?string $developerPayload;

    /** Details of the checked image. */
    public ?CopyleaksImagePlagiarismScannedImageModel $scannedImage;

    /** Copies of the image found on the web, and their counts. */
    public ?CopyleaksImagePlagiarismMatchesModel $matches;

    public function __construct(
        ?string $developerPayload = null,
        ?CopyleaksImagePlagiarismScannedImageModel $scannedImage = null,
        ?CopyleaksImagePlagiarismMatchesModel $matches = null
    ) {
        $this->developerPayload = $developerPayload;
        $this->scannedImage = $scannedImage;
        $this->matches = $matches;
    }

    /** Unknown keys are ignored; a value of an unexpected type is read as missing. */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['developerPayload'] ?? null) ? $data['developerPayload'] : null,
            is_array($data['scannedImage'] ?? null) ? CopyleaksImagePlagiarismScannedImageModel::fromArray($data['scannedImage']) : null,
            is_array($data['matches'] ?? null) ? CopyleaksImagePlagiarismMatchesModel::fromArray($data['matches']) : null
        );
    }
}
