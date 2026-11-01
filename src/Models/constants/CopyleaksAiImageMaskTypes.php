<?php

namespace Copyleaks;

/**
 * Values of AI Image Detection maskType.
 */
class CopyleaksAiImageMaskTypes
{
    /** RLE mask of the regions flagged as AI-generated (result.starts, result.lengths). The default. */
    public const BINARY = "binary";

    /** Also a 0-4 confidence band per RLE run, in result.labels. */
    public const HEATMAP = "heatmap";

    /** No mask: the response has no result object. Synchronous check only. */
    public const NONE = "none";
}
