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

class SubmissionWebhooks
{
  /**
   * This webhook event is triggered once the scan status changes.
   * Use the special token {STATUS} to track the current scan status.
   * This special token will automatically be replaced by the Copyleaks servers with the optional values: completed, error, creditsChecked and indexed.
   * Read more about webhooks: https://api.copyleaks.com/documentation/v3/webhooks
   */
  public string $status;
  /**
   * Http endpoint to be triggered while the scan is still running and a new result is found.
   * This is useful when the report is being viewed by the user in real time so the results will load gradually as they are found.
   */
  public ?string $newResult;

  /**
   * @param string status This webhook event is triggered once the scan status changes. Use the special token {STATUS} to track the current scan status. This special token will automatically be replaced by the Copyleaks servers with the optional values: completed, error, creditsChecked and indexed. Read more about webhooks: https://api.copyleaks.com/documentation/v3/webhooks
   * @param string newResult Http endpoint to be triggered while the scan is still running and a new result is found. This is useful when the report is being viewed by the user in real time so the results will load gradually as they are found.
   */
  public function __construct(string $status, string $newResult = null)
  {
    $this->newResult = $newResult;
    $this->status = $status;
  }
}
