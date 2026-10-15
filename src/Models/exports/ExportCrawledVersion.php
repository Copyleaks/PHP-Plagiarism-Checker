<?php

namespace Copyleaks;

class ExportCrawledVersion
{
  /** HTTP URL to upload the data to. */
  public string $endpoint;
  /** HTTP method of the upload, e.g. "POST". */
  public string $verb;
  /** Upload request headers, such as Authorization, as [["header-key", "header-value"], ...]. */
  public ?array $headers;
  /**
   * @param string $endpoint
   * @param string $verb
   * @param array $headers
   */
  public function __construct(
    string $endpoint,
    string $verb,
    ?array $headers = null
  ) {
    $this->endpoint = $endpoint;
    $this->verb = $verb;
    $this->headers = $headers;
  }
}
