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
 * Information about the analyzed video.
 */
class CopyleaksAiVideoDetectionVideoInfoModel
{
    /**
     * Total duration of the video, in seconds.
     */
    public ?float $duration;

    /**
     * Metadata extracted from the video file, when available.
     */
    public ?CopyleaksVideoMetadataModel $metadata;

    public function __construct(?float $duration = null, ?CopyleaksVideoMetadataModel $metadata = null)
    {
        $this->duration = $duration;
        $this->metadata = $metadata;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            is_numeric($data['duration'] ?? null) ? (float) $data['duration'] : null,
            is_array($data['metadata'] ?? null) ? CopyleaksVideoMetadataModel::fromArray($data['metadata']) : null
        );
    }
}
