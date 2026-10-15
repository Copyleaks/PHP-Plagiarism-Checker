<?php

namespace Copyleaks;

class ExportResults extends ExportTarget implements \JsonSerializable
{
  /**
   * Result ID, from the completed webhook.
   * https://docs.copyleaks.com/reference/data-types/authenticity/webhooks/scan-completed
   */
  public string $id;
  /**
   * @param string $id
   * @param string $endpoint
   * @param string $verb
   * @param array|null $headers
   */
  public function __construct(
    string $id,
    string $endpoint,
    string $verb,
    ?array $headers = null
  ) {
    $this->id = $id;
    parent::__construct($endpoint, $verb, $headers);
  }

  /** Keeps `id` first in the JSON, as in earlier releases; inherited properties would otherwise come first. */
  public function jsonSerialize(): array
  {
    return ['id' => $this->id] + get_object_vars($this);
  }
}
