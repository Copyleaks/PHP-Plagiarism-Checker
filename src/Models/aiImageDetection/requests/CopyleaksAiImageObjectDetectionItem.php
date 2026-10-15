<?php

namespace Copyleaks;

/**
 * One object to locate and segment in an image (AI Image Detection objectDetection).
 */
class CopyleaksAiImageObjectDetectionItem
{
    /** E.g. "face" or "hand". 1 to 32 characters. */
    public string $object;

    /** @param string $object */
    public function __construct(string $object)
    {
        $this->object = $object;
    }
}
