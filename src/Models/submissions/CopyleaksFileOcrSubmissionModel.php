<?php

namespace Copyleaks;

class CopyleaksFileOcrSubmissionModel extends CopyleaksFileSubmissionModel
{
  /**
   * Content language; must be on the OCR supported languages list, see Copyleaks::getOCRSupportedLanguages().
   */
  public string $langCode;
  /**
   * @param string $langCode
   * @param string $base64
   * @param string $filename
   * @param SubmissionProperties $properties
   */
  public function __construct(
    string $langCode,
    string $base64,
    string $filename,
    SubmissionProperties $properties
  ) {
    parent::__construct($base64, $filename, $properties);
    $this->langCode = $langCode;
  }
}
