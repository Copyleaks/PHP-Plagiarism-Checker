<?php

namespace Copyleaks;

class CopyleaksFileSubmissionModel extends CopyleaksSubmissionModel
{
  /** The file, base64-encoded. To scan plain text, base64-encode it. */
  public string $base64;
  /** File name shown in the report; the extension must match the file type. */
  public string $filename;
  public SubmissionProperties $properties;
  /**
   * @param string $base64
   * @param string $filename
   * @param SubmissionProperties $properties
   */
  public function __construct(
    string $base64,
    string $filename,
    SubmissionProperties $properties
  ) {
    parent::__construct($properties);
    $this->base64 = $base64;
    $this->filename = $filename;
  }
}
