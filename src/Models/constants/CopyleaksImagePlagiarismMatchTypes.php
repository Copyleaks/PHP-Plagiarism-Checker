<?php

namespace Copyleaks;

/**
 * Values of Image Plagiarism matches.internet[].matchType.
 */
class CopyleaksImagePlagiarismMatchTypes
{
    /** Exact or near-exact copy. */
    public const FULL = 0;

    /** Cropped, resized, recolored or otherwise modified copy. */
    public const PARTIAL = 1;
}
