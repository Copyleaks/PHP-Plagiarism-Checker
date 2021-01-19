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

class CopyleaksStartRequestModel
{
  /**
   * A list of scans that you submitted for a check-credits scan and that you would like to submit for a full scan.
   */
  public array $trigger;
  /**
   * When set to ignore (ignore = 1) the trigger scans will start running even if some of them are in error mode, when set to cancel (cancel = 0) the request will be cancelled if any error was found.
   */
  public int $errorHandling;
  /** 
   * @param string[] $trigger A list of scans that you submitted for a check-credits scan and that you would like to submit for a full scan.
   * @param CopyleaksStartErrorHandlings $errorHandling When set to ignore (ignore = 1) the trigger scans will start running even if some of them are in error mode, when set to cancel (cancel = 0) the request will be cancelled if any error was found.
   */
  public function __construct(array $trigger, int $errorHandling = CopyleaksStartErrorHandlings::IGNORE)
  {
    $this->trigger = $trigger;
    $this->errorHandling = $errorHandling;
  }
}

class CopyleaksStartErrorHandlings
{
  /**
   * The request will be cancelled if any error was found.
   */
  const CANCEL = 0;
  /**
   * The trigger scans will start running even if some of them are in error mode
   */
  const IGNORE = 1;
}
