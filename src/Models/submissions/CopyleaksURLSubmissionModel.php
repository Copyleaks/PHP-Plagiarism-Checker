<?php

namespace Copyleaks;

class CopyleaksURLSubmissionModel extends CopyleaksSubmissionModel
{
  public string $url;
  public SubmissionProperties $properties;
  /** HTTP method for fetching the url: 'GET', 'POST' or 'PUT'. null = server default ('GET'). */
  public ?string $verb;
  /**
   * Headers for fetching the url, as [["header-key", "header-value"], ...]. When set, no Copyleaks headers are
   * added. Use Set-Cookie for cookies.
   */
  public ?array $headers;
  /**
   * @param string $url
   * @param SubmissionProperties $properties
   * @param string|null $verb
   * @param array|null $headers
   */
  public function __construct(string $url, SubmissionProperties $properties, ?string $verb = null, ?array $headers = null)
  {
    $this->url = $url;
    $this->properties = $properties;
    $this->verb = $verb;
    $this->headers = $headers;
    parent::__construct($properties);
  }
}
