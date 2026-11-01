<?php

namespace Copyleaks;

class CopyleaksDeleteRequestModel
{
  /** Scans to delete, as {id: string}[]. */
  public array $scans;
  /** Remove every trace of the scans, including from the internal database, so they no longer appear as results. */
  public bool $purge;
  /** Called with a POST (no body) when the deletion completes. An empty string means no webhook. */
  public string $completionWebhook;
  /** Headers for the completion webhook, as [["header-key", "header-value"], ...]. */
  public ?array $headers;
  /**
   * @param {id:string}[] $scans
   * @param Boolean $purge
   * @param string $completionWebhook
   * @param array|null $headers
   */
  public function __construct(array $scans, bool $purge = false, string $completionWebhook = "", ?array $headers = null)
  {
    $this->scans = $scans;
    $this->purge = $purge;
    $this->completionWebhook = $completionWebhook;
    $this->headers = $headers;
  }
}
