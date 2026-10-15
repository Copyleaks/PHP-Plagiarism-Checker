<?php

namespace Copyleaks;

use Copyleaks\ObjectFilter;

class SubmissionProperties
{
  public SubmissionWebhooks $webhooks;
  /** Also return the report in HTML (text only by default). */
  public ?bool $includeHtml;
  /**
   * Custom payload returned in the webhooks.
   * https://docs.copyleaks.com/reference/data-types/authenticity/webhooks/overview
   */
  public ?string $developerPayload;
  /** Return mock results without using credits, for integration tests. Turn off in production. */
  public ?bool $sandbox;
  /** Hours the scan is kept on the server; after that it is deleted. */
  public ?int $expiration;
  /** 1 (fastest, finds the most plagiarism) to 5 (slowest, also finds the smallest matches). */
  public ?int $sensitivityLevel;
  /** Check the document for cheating; a detection adds a scan alert to the completed webhook. */
  public ?bool $cheatDetection;
  /** See SubmissionActions. */
  public ?int $action;
  public ?SubmissionAuthor $author;
  public ?SubmissionFilter $filters;
  public ?SubmissionScanning $scanning;
  public ?SubmissionIndexing $indexing;
  public ?SubmissionExclude $exclude;
  public ?SubmissionPDF $pdf;
  public ?SubmissionSensitiveData $sensitiveDataProtection;

  /** Algorithm goal, see ScanMethodAlgorithm. */
  public ?int $scanMethodAlgorithm;

  /** CustomMetadata[] stored with the document in a repository and returned on repository results. */
  public ?array $customMetadata;

  public ?AIGeneratedText $aiGeneratedText;

  /**
   * PDF report language: 'en' (default), 'es', 'pt', 'fr', 'de' or 'it'. Lowercase and case-sensitive
   * ('EN' and 'en-US' are rejected). Used only when pdf->create is true.
   */
  public ?string $displayLanguage;

  public ?AISourceMatch $aiSourceMatch;

  /**
   * IANA time zone of the scan time in the PDF report, e.g. 'America/New_York' (case-sensitive).
   * Defaults to the user's country time zone, or UTC.
   */
  public ?string $scanTimeZone;

  /** For analytics. */
  public ?SubmissionCourse $course;

  /** For analytics. */
  public ?SubmissionAssignment $assignment;

  /** For analytics. */
  public ?SubmissionInstitution $institution;

  /** Grammar Checker settings. */
  public ?SubmissionWritingFeedback $writingFeedback;

  /** Gen-AI Overview settings. */
  public ?SubmissionOverview $overview;

  /** Internal AI Source Match settings. */
  public ?InternalAISourceMatch $internalAiSourceMatch;

  /** Citation Checker settings. */
  public ?SubmissionReferences $references;

  /**
   * @param SubmissionWebhooks $webhooks
   * @param bool $includeHtml
   * @param string $developerPayload
   * @param bool $sandbox
   * @param int $expiration
   * @param int $sensitivityLevel
   * @param bool $cheatDetection
   * @param SubmissionActions $action
   * @param SubmissionAuthor $author
   * @param SubmissionFilter $filters
   * @param SubmissionScanning $scanning
   * @param SubmissionIndexing $indexing
   * @param SubmissionExclude $exclude
   * @param SubmissionPDF $pdf
   * @param SubmissionSensitiveData $sensitiveDataProtection
   * @param ScanMethodAlgorithm $scanMethodAlgorithm
   * @param CustomMetadata[] $customMetadata
   * @param AIGeneratedText $aiGeneratedText
   * @param string $displayLanguage
   * @param AISourceMatch $aiSourceMatch
   * @param string $scanTimeZone
   * @param SubmissionCourse $course
   * @param SubmissionAssignment $assignment
   * @param SubmissionInstitution $institution
   * @param SubmissionWritingFeedback $writingFeedback
   * @param SubmissionOverview $overview
   * @param InternalAISourceMatch $internalAiSourceMatch
   * @param SubmissionReferences $references
   */
  public function __construct(
    SubmissionWebhooks $webhooks,
    ?bool $includeHtml = null,
    ?string $developerPayload = null,
    ?bool $sandbox = null,
    ?int $expiration = null,
    ?int $sensitivityLevel = null,
    ?bool $cheatDetection = null,
    ?int $action = null,
    ?SubmissionAuthor $author = null,
    ?SubmissionFilter $filters = null,
    ?SubmissionScanning $scanning = null,
    ?SubmissionIndexing $indexing = null,
    ?SubmissionExclude $exclude = null,
    ?SubmissionPDF $pdf = null,
    ?SubmissionSensitiveData $sensitiveDataProtection = null,
    ?int $scanMethodAlgorithm = null,
    ?array $customMetadata = null,
    ?AIGeneratedText $aiGeneratedText = null,
    ?string $displayLanguage = null,
    ?AISourceMatch $aiSourceMatch = null,
    ?string $scanTimeZone = null,
    ?SubmissionCourse $course = null,
    ?SubmissionAssignment $assignment = null,
    ?SubmissionInstitution $institution = null,
    ?SubmissionWritingFeedback $writingFeedback = null,
    ?SubmissionOverview $overview = null,
    ?InternalAISourceMatch $internalAiSourceMatch = null,
    ?SubmissionReferences $references = null
  ) {
    $this->webhooks = $webhooks;
    $this->includeHtml = $includeHtml;
    $this->developerPayload = $developerPayload;
    $this->sandbox = $sandbox;
    $this->expiration = $expiration;
    $this->sensitivityLevel = $sensitivityLevel;
    $this->cheatDetection = $cheatDetection;
    $this->action = $action;
    $this->author = $author;
    $this->filters = $filters;
    $this->scanning = $scanning;
    $this->indexing = $indexing;
    $this->exclude = $exclude;
    $this->pdf = $pdf;
    $this->sensitiveDataProtection = $sensitiveDataProtection; 
    $this->scanMethodAlgorithm = $scanMethodAlgorithm;
    $this->customMetadata = $customMetadata;
    $this->aiGeneratedText = $aiGeneratedText;
    $this->displayLanguage = $displayLanguage;
    $this->aiSourceMatch = $aiSourceMatch;
    $this->scanTimeZone = $scanTimeZone;
    $this->course = $course;
    $this->assignment = $assignment;
    $this->institution = $institution;
    $this->writingFeedback = $writingFeedback;
    $this->overview = $overview;
    $this->internalAiSourceMatch = $internalAiSourceMatch;
    $this->references = $references;
  }
}
