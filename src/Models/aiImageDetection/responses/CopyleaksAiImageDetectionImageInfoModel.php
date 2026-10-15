<?php

namespace Copyleaks;

use Copyleaks\CopyleaksImageShapeModel;
use Copyleaks\CopyleaksImageMetadataModel;

class CopyleaksAiImageDetectionImageInfoModel
{
    public ?CopyleaksImageShapeModel $shape;

    /** Metadata extracted from the image, if any. */
    public ?CopyleaksImageMetadataModel $metadata;

    public function __construct(
        ?CopyleaksImageShapeModel $shape = null,
        ?CopyleaksImageMetadataModel $metadata = null
    ) {
        $this->shape = $shape;
        $this->metadata = $metadata;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            isset($data['shape']) ? CopyleaksImageShapeModel::fromArray($data['shape']) : null,
            isset($data['metadata']) ? CopyleaksImageMetadataModel::fromArray($data['metadata']) : null
        );
    }
}
