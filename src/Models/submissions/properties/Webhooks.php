<?php

namespace Copyleaks;

class SubmissionWebhooks
{
  /**
   * Called when the scan status changes. A {STATUS} token in the URL is replaced with completed, error,
   * creditschecked or indexed. https://docs.copyleaks.com/reference/data-types/authenticity/webhooks/overview
   */
  public string $status;
  /** Called for each new result while the scan runs, so a live report can load results as they are found. */
  public ?string $newResult;

  /** Headers for the newResult webhook, as [["header-key", "header-value"], ...]. */
  public ?array $newResultHeaders;

  /** Headers for the status webhook, as [["header-key", "header-value"], ...]. */
  public ?array $statusHeaders;

  /**
   * @param string $status
   * @param string $newResult
   * @param array $newResultHeaders
   * @param array $statusHeaders
   */
  public function __construct(string $status, ?string $newResult = null, ?array $newResultHeaders = null, ?array $statusHeaders = null)
  {
    $this->newResult = $newResult;
    $this->status = $status;  
    $this->newResultHeaders = $newResultHeaders;
    $this->statusHeaders = $statusHeaders;
  }
}
