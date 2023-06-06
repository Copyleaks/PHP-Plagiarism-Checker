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

class ReportCustomizationColors
{
    /**
     * The color of the main strip in the header
     */
  public ?string $mainStrip;

    /**
     * The color for titles in copyleaks result report
     */
  public ?string $titles;
    /**
     * The highlight color for identical matches
     */
  public ?string $identical;
    /**
     * The highlight color for minor changes matches
     */
  public ?string $minorChanges;
    /**
     * The highlight color for related meaning matches
     */
  public ?string $relatedMeaning;


    /**
   *@param string $mainStrip - The color of the main strip in the header
   *@param string $titles - The color for titles in copyleaks result report
   *@param string $identical - The highlight color for identical matches
   *@param string $minorChanges - The highlight color for minor changes matches
   *@param string $relatedMeaning - The highlight color for related meaning matches
   */
  public function __construct(
    ?string $mainStrip = null, 
    ?string $titles = null, 
    ?string $identical = null, 
    ?string $minorChanges = null, 
    ?string $relatedMeaning = null
    )
  {
    $this->mainStrip = $mainStrip;
    $this->titles = $titles;
    $this->identical = $identical;
    $this->minorChanges = $minorChanges;
    $this->relatedMeaning = $relatedMeaning;
  }
}
