<?php

namespace Copyleaks;

class ExportResults
{
  /**
   * Result ID, from the completed webhook.
   * https://docs.copyleaks.com/reference/data-types/authenticity/webhooks/scan-completed
   */
  public string $id;
  /** HTTP URL to upload the data to. */
  public string $endpoint;
  /** HTTP method of the upload, e.g. "POST". */
  public string $verb;
  /** Upload request headers, such as Authorization, as [["header-key", "header-value"], ...]. */
  public ?array $headers;
  /**
   * @param string $id
   * @param string $endpoint
   * @param string $verb
   * @param array $headers
   */
  public function __construct(
    string $id,
    string $endpoint,
    string $verb,
    ?array $headers = null
  ) {
    $this->id = $id;
    $this->endpoint = $endpoint;
    $this->verb = $verb;
    $this->headers = $headers;
  }
}
