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

class AIGeneratedText
{
    /**
     * Detects whether the text was written by an AI.
     */
  public bool $detect;

  /**
   * Control the behavior of the AI detection, from 1 to 3. Leave null to get the server default (2).
   */
  public ?int $sensitivity;

  /**
   * AI Logic: explain why the text was detected as AI. Check inner properties for more details.
   */
  public ?AIGeneratedTextExplain $explain;

    /**
   * @param bool $detect Detects whether the text was written by an AI.
   * @param int|null $sensitivity Control the behavior of the AI detection, from 1 to 3. null = server default (2).
   * @param AIGeneratedTextExplain|null $explain AI Logic settings. null = server default (disabled).
   */
  public function __construct(
    bool $detect = false,
    ?int $sensitivity = null,
    ?AIGeneratedTextExplain $explain = null)
  {
    $this->detect = $detect;
    $this->sensitivity = $sensitivity;
    $this->explain = $explain;
  }
}
