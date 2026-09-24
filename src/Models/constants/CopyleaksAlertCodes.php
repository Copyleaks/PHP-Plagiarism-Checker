<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

/**
 * Codes of the scan alerts sent in the completed webhook (notifications.alerts[].code).
 * All codes below belong to alert category 2 (AI content detection).
 * Alert severity ranges from 0 (very low) to 4 (very high).
 */
class CopyleaksAlertCodes
{
    /**
     * AI-generated text was detected in the scanned text (category 2, severity 4).
     * The alert's additionalData holds the AI text detection result as a JSON string.
     */
    public const SUSPECTED_AI_TEXT = "suspected-ai-text";

    /**
     * AI text detection failed.
     */
    public const AI_DETECTION_FAILED = "ai-detection-failed";

    /**
     * AI text detection did not run because the language of the text is not supported.
     */
    public const AI_DETECTION_LANG_NOT_SUPPORTED = "ai-detection-lang-not-supported";

    /**
     * AI text detection did not run because the text is too short.
     */
    public const AI_DETECTION_TEXT_TOO_SHORT = "ai-detection-text-too-short";

    /**
     * AI text detection did not run because the file type is not supported.
     */
    public const FILE_TYPE_NOT_SUPPORTED = "file-type-not-supported";
}

?>