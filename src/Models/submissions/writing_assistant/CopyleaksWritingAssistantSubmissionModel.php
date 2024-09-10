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

class CopyleaksWritingAssistantSubmissionModel
{
    public string $text;
    public ?bool $sandbox;
    public ?string $language;
    public ?ScoreWeights $score;

    /**
     * @param string $text Text to produce Writing Assistant report for. 1 >= characters <= 25000
     * @param bool|null $sandbox Use sandbox mode to test your integration with the Copyleaks API without consuming any credits.
     * @param string|null $language The language code of your content. The selected language should be on the Supported Languages list above.If the 'language' field is not supplied , our system will automatically detect the language of the content.
     * @param ScoreWeights|null
     */
    public function __construct(string $text, ?bool $sandbox = null, ?string $language = null, ?ScoreWeights $score = null)
    {
        $this->text = $text;
        $this->sandbox = $sandbox;
        $this->language = $language;
        $this->score = $score;
    }
}
