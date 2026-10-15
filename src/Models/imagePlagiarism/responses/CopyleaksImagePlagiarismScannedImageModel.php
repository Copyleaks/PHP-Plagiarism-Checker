<?php

namespace Copyleaks;

/**
 * Details of the image that was checked by the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismScannedImageModel extends CopyleaksScannedDocumentModel
{
    /** Pixels. */
    public ?int $width;

    /** Pixels. */
    public ?int $height;

    /** The filename you provided. */
    public ?string $filename;

    public function __construct(
        string $scanId,
        int $actualCredits,
        int $expectedCredits,
        string $creationTime,
        ?int $width = null,
        ?int $height = null,
        ?string $filename = null
    ) {
        parent::__construct($scanId, $actualCredits, $expectedCredits, $creationTime);
        $this->width = $width;
        $this->height = $height;
        $this->filename = $filename;
    }

    /** @return static|null */
    public static function fromArray(?array $data): ?self
    {
        $model = parent::fromArray($data);
        if ($model !== null) {
            $model->width = is_numeric($data['width'] ?? null) ? (int) $data['width'] : null;
            $model->height = is_numeric($data['height'] ?? null) ? (int) $data['height'] : null;
            $model->filename = is_string($data['filename'] ?? null) ? $data['filename'] : null;
        }
        return $model;
    }
}
