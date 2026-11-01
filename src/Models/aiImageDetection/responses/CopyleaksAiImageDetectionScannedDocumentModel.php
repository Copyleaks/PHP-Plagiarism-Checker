<?php

namespace Copyleaks;

/**
 * Same fields as CopyleaksScannedDocumentModel.
 */
class CopyleaksAiImageDetectionScannedDocumentModel extends CopyleaksScannedDocumentModel
{
    /** Declared here to keep the return type of earlier releases. */
    public static function fromArray(?array $data): ?self
    {
        return parent::fromArray($data);
    }
}
