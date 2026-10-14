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
 * Grammar Checker settings of a scan (properties.writingFeedback).
 */
class SubmissionWritingFeedback
{
  /**
   * Enable the Grammar Checker for grammar, spelling and more. Leave null to get the server default (false).
   */
  public ?bool $enable;

  /**
   * The weight of each correction category in the overall writing score.
   * Each weight left null uses the server default (1.0).
   */
  public ?ScoreWeights $score;

  /**
   * @param bool|null $enable Enable the Grammar Checker. null = server default (false).
   * @param ScoreWeights|null $score The weight of each correction category in the overall writing score.
   */
  public function __construct(?bool $enable = null, ?ScoreWeights $score = null)
  {
    $this->enable = $enable;
    $this->score = $score;
  }
}
