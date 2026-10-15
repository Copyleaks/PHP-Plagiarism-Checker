<?php

namespace Copyleaks;

class SubmissionExclude
{
  public bool $quotes;
  public bool $references;
  public bool $tableOfContents;
  public bool $titles;
  /** For HTML documents, exclude text repeated across the site, such as the header and footer. */
  public bool $htmlTemplate;

  public bool $citations;

  /** Exclude text found in these other documents. */
  public array $documentTemplateIds;

  public ?ExcludeCode $code;


  /**
   * @param bool $quotes
   * @param bool $references
   * @param bool $tableOfContents
   * @param bool $titles
   * @param bool $htmlTemplate
   * @param bool $citations
   * @param string[] $documentTemplateIds
   * @param ExcludeCode $code
   */
  public function __construct(
    bool $quotes = false,
    bool $references = false,
    bool $tableOfContents = false,
    bool $titles = false,
    bool $htmlTemplate = false,
    bool $citations = false,
    array $documentTemplateIds = array(),
    ?ExcludeCode $code = null

  ) {
    $this->quotes = $quotes;
    $this->references = $references;
    $this->tableOfContents = $tableOfContents;
    $this->titles = $titles;
    $this->htmlTemplate = $htmlTemplate;
    $this->citations = $citations;
    $this->documentTemplateIds = $documentTemplateIds;
    $this->code = $code;
  }
}
