<?php

namespace Copyleaks;

/**
 * An object found by AI Image Detection objectDetection, with its segmentation mask.
 */
class CopyleaksAiImageDetectedObjectModel
{
    /** The object name from the request. */
    public string $object;

    /** RLE segmentation mask (starts and lengths in the flattened image array). */
    public ?CopyleaksAiImageDetectionResultModel $mask;

    public function __construct(string $object, ?CopyleaksAiImageDetectionResultModel $mask = null)
    {
        $this->object = $object;
        $this->mask = $mask;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            (string) ($data['object'] ?? ''),
            is_array($data['mask'] ?? null) ? CopyleaksAiImageDetectionResultModel::fromArray($data['mask']) : null
        );
    }
}
