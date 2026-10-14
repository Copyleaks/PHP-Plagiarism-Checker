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
 * Copies of the image found by the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismMatchesModel
{
    /**
     * Copies of the image found on the web.
     * @var CopyleaksImagePlagiarismInternetMatchModel[]
     */
    public array $internet;

    /** Match counts. */
    public ?CopyleaksImagePlagiarismScoreModel $score;

    public function __construct(array $internet = [], ?CopyleaksImagePlagiarismScoreModel $score = null)
    {
        $this->internet = $internet;
        $this->score = $score;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $internet = [];
        if (is_array($data['internet'] ?? null)) {
            foreach ($data['internet'] as $item) {
                if (is_array($item)) {
                    $internet[] = CopyleaksImagePlagiarismInternetMatchModel::fromArray($item);
                }
            }
        }

        return new self(
            $internet,
            is_array($data['score'] ?? null) ? CopyleaksImagePlagiarismScoreModel::fromArray($data['score']) : null
        );
    }
}
