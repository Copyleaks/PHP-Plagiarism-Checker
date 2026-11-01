<?php

namespace Copyleaks;

/**
 * Metadata extracted from the image. Same fields as CopyleaksMediaMetadataModel.
 */
class CopyleaksImageMetadataModel extends CopyleaksMediaMetadataModel
{
    /** Declared here to keep the return type of earlier releases. */
    public static function fromArray(?array $data): ?self
    {
        return parent::fromArray($data);
    }
}
