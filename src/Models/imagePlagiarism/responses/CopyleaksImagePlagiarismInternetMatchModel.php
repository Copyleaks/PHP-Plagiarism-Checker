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
 * A copy of the image found on the web.
 */
class CopyleaksImagePlagiarismInternetMatchModel
{
    /** URL of the matching image. */
    public ?string $url;

    /** 0 = full match, 1 = partial match. See CopyleaksImagePlagiarismMatchTypes. */
    public ?int $matchType;

    /**
     * The web pages where the matching image was found. Empty when none were returned.
     * @var CopyleaksImagePlagiarismWebPageModel[]
     */
    public array $webPages;

    public function __construct(?string $url = null, ?int $matchType = null, array $webPages = [])
    {
        $this->url = $url;
        $this->matchType = $matchType;
        $this->webPages = $webPages;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $webPages = [];
        if (is_array($data['webPages'] ?? null)) {
            foreach ($data['webPages'] as $page) {
                if (is_array($page)) {
                    $webPages[] = CopyleaksImagePlagiarismWebPageModel::fromArray($page);
                }
            }
        }

        return new self(
            is_string($data['url'] ?? null) ? $data['url'] : null,
            is_numeric($data['matchType'] ?? null) ? (int) $data['matchType'] : null,
            $webPages
        );
    }
}
