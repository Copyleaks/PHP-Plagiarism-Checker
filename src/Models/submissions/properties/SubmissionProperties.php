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

class SubmissionProperties
{
  /**
   * Check inner properties for more details.
   */
  public SubmissionWebhooks $webhooks;
  /**
   * By default, Copyleaks will present the report in text format. If set to true, Copyleaks will also include html format.
   */
  public ?bool $includeHtml;
  /**
   * Add custom developer payload that will then be provided on the webhooks.
   * https://api.copyleaks.com/documentation/v3/webhooks
   */
  public ?string $developerPayload;
  /**
   * You can test the integration with the Copyleaks API for free using the sandbox mode.
   *
   * You will be able to submit content for a scan and get back mock results, simulating the way Copyleaks will work to make sure that you successfully integrated with the API.
   *
   * Turn off this feature on production environment.
   */
  public ?bool $sandbox;
  /**
   * Specify the maximum life span of a scan in hours on the Copyleaks servers.
   *
   * When expired, the scan will be deleted and will no longer be accessible.
   */
  public ?int $expiration;
  /**
   * You can control the level of plagiarism sensitivity that will be identified according to the speed of the scan.
   * If you prefer a faster scan with the results that contains the highest amount of plagiarism choose 1,
   * and if a slower, more comprehensive scan, that will also detect the smallest instances choose 5.
   */
  public ?int $sensitivityLevel;
  /**
   * When set to true the submitted document will be checked for cheating. If a cheating will be detected, a scan alert will be added to the completed webhook.
   */
  public ?bool $cheatDetection;
  /**
   * SubmissionActions - Types of content submission actions.
   *
   * * Possible values:
   *  * Scan: Start scan immediately.
   *  * Check Credits: Check how many credits will be used for this scan.
   *  * Index Only: Only index the file in the Copyleaks internal database. No credits will be used.
   */
  public ?int $action;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionAuthor $author;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionFilter $filters;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionScanning $scanning;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionIndexing $indexing;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionExclude $exclude;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionPDF $pdf;
  /**
   * Check inner properties for more details.
   */
  public ?SubmissionSensitiveData $sensitiveDataProtection;

  /**
   *@param SubmissionWebhooks $webhooks - Check inner properties for more details.
   *@param bool $includeHtml - By default, Copyleaks will present the report in text format. If set to true, Copyleaks will also include html format.
   *@param string $developerPayload - Add custom developer payload that will then be provided on the webhooks. https://api.copyleaks.com/documentation/v3/webhooks
   *@param bool $sandbox - You can test the integration with the Copyleaks API for free using the sandbox mode. You will be able to submit content for a scan and get back mock results, simulating the way Copyleaks will work to make sure that you successfully integrated with the API. Turn off this feature on production environment.
   *@param int $expiration - Specify the maximum life span of a scan in hours on the Copyleaks servers. When expired, the scan will be deleted and will no longer be accessible.
   *@param int $sensitivityLevel - You can control the level of plagiarism sensitivity that will be identified according to the speed of the scan. If you prefer a faster scan with the results that contains the highest amount of plagiarism choose 1, and if a slower, more comprehensive scan, that will also detect the smallest instances choose 5.
   *@param bool $cheatDetection - When set to true the submitted document will be checked for cheating. If a cheating will be detected, a scan alert will be added to the completed webhook.
   *@param SubmissionActions $action - Types of content submission actions. Possible values: Scan: Start scan immediately. Check Credits: Check how many credits will be used for this scan. Index Only: Only index the file in the Copyleaks internal database. No credits will be used.
   *@param SubmissionAuthor $author - Check inner properties for more details.
   *@param SubmissionFilter $filters - Check inner properties for more details.
   *@param SubmissionScanning $scanning - Check inner properties for more details.
   *@param SubmissionIndexing $indexing - Check inner properties for more details.
   *@param SubmissionExclude $exclude - Check inner properties for more details.
   *@param SubmissionPDF $pdf - Check inner properties for more details.
   *@param SubmissionSensitiveData $sensitiveDataProtection - Check inner properties for more details.
   */
  public function __construct(
    SubmissionWebhooks $webhooks,
    ?bool $includeHtml = null,
    ?string $developerPayload = null,
    ?bool $sandbox = null,
    ?int $expiration = null,
    ?int $sensitivityLevel = null,
    ?bool $cheatDetection = null,
    ?int $action = null,
    ?SubmissionAuthor $author = null,
    ?SubmissionFilter $filters = null,
    ?SubmissionScanning $scanning = null,
    ?SubmissionIndexing $indexing = null,
    ?SubmissionExclude $exclude = null,
    ?SubmissionPDF $pdf = null,
    ?SubmissionSensitiveData $sensitiveDataProtection = null
  ) {
    $this->webhooks = $webhooks;
    $this->includeHtml = $includeHtml;
    $this->developerPayload = $developerPayload;
    $this->sandbox = $sandbox;
    $this->expiration = $expiration;
    $this->sensitivityLevel = $sensitivityLevel;
    $this->cheatDetection = $cheatDetection;
    $this->action = $action;
    $this->author = $author;
    $this->filters = $filters;
    $this->scanning = $scanning;
    $this->indexing = $indexing;
    $this->exclude = $exclude;
    $this->pdf = $pdf;
    $this->sensitiveDataProtection = $sensitiveDataProtection;
  }
}
