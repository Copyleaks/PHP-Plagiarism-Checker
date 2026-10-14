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
 * Metadata extracted from a video file (for example C2PA provenance data), when available.
 */
class CopyleaksVideoMetadataModel
{
    /**
     * When the video was created.
     */
    public ?string $issuedTime;

    /**
     * The AI service or tool that created the video.
     */
    public ?string $issuedBy;

    /**
     * The application or device used to create the video.
     */
    public ?string $appOrDeviceUsed;

    /**
     * A summary of how the video was generated.
     */
    public ?string $contentSummary;

    public function __construct(
        ?string $issuedTime = null,
        ?string $issuedBy = null,
        ?string $appOrDeviceUsed = null,
        ?string $contentSummary = null
    ) {
        $this->issuedTime = $issuedTime;
        $this->issuedBy = $issuedBy;
        $this->appOrDeviceUsed = $appOrDeviceUsed;
        $this->contentSummary = $contentSummary;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_string($data['issuedTime'] ?? null) ? $data['issuedTime'] : null,
            is_string($data['issuedBy'] ?? null) ? $data['issuedBy'] : null,
            is_string($data['appOrDeviceUsed'] ?? null) ? $data['appOrDeviceUsed'] : null,
            is_string($data['contentSummary'] ?? null) ? $data['contentSummary'] : null
        );
    }
}
