<?php

namespace Copyleaks;

class CopyleaksExportModel
{
  /** Called when the export completes. */
  public string $completionWebhook;
  /** ExportResults[] to export. null exports none. */
  public ?array $results;
  /** The crawled version of the submitted content. null exports none. */
  public ?ExportCrawledVersion $crawledVersion;
  /**
   * Webhook retries before giving up. A high value (12) can delay the completionWebhook; a low value (1) can
   * fail while your service has a temporary problem.
   */
  public ?int $maxRetries;
  /**
   * Returned in the Export-Completed webhook.
   * https://docs.copyleaks.com/reference/data-types/authenticity/webhooks/export-completed
   */
  public ?string $developerPayload;
  /** Allowed only when `properties.pdf.create` was true on the scan. */
  public ?ExportPdfReport $pdfReport;
  /** Headers for the completion webhook, as [["header-key", "header-value"], ...]. */
  public ?array $completionWebhookHeaders;
  /** AI Text Detection report. Allowed only when `properties.aiGeneratedText.detect` was true on the scan. */
  public ?ExportAiDetection $aiDetection;
  /** Grammar Checker report. Allowed only when `properties.writingFeedback.enable` was true on the scan. */
  public ?ExportWritingFeedback $writingFeedback;
  /** Gen-AI Overview report. Allowed only when `properties.overview.enable` was true on the scan. */
  public ?ExportOverview $overview;
  /**
   * @param string $completionWebhook
   * @param ExportResults[]|null $results
   * @param ExportCrawledVersion|null $crawledVersion
   * @param ExportPdfReport $pdfReport
   * @param int $maxRetries
   * @param string $developerPayload
   * @param array|null $completionWebhookHeaders
   * @param ExportAiDetection|null $aiDetection
   * @param ExportWritingFeedback|null $writingFeedback
   * @param ExportOverview|null $overview
   */
  public function __construct(
    string $completionWebhook,
    ?array $results = null,
    ?ExportCrawledVersion $crawledVersion = null,
    ?ExportPdfReport $pdfReport = null,
    ?int $maxRetries = null,
    ?string $developerPayload = null,
    ?array $completionWebhookHeaders = null,
    ?ExportAiDetection $aiDetection = null,
    ?ExportWritingFeedback $writingFeedback = null,
    ?ExportOverview $overview = null
  ) {
    $this->completionWebhook = $completionWebhook;
    $this->results = $results;
    $this->crawledVersion = $crawledVersion;
    $this->pdfReport = $pdfReport;
    $this->maxRetries = $maxRetries;
    $this->developerPayload = $developerPayload;
    $this->completionWebhookHeaders = $completionWebhookHeaders;
    $this->aiDetection = $aiDetection;
    $this->writingFeedback = $writingFeedback;
    $this->overview = $overview;
  }
}
