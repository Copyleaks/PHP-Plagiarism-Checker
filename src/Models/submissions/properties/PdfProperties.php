<?php

namespace Copyleaks;

class SubmissionPDF
{
  /** Generate a PDF report for this scan. */
  public bool $create;
  /** PDF report title. null leaves it out. */
  public ?string $title;
  /** PDF report logo, a base64-encoded PNG. null leaves it out. */
  public ?string $largeLogo;
  /** Align the report text right to left. */
  public bool $rtl;

  /** Legacy, see PdfReportVersion; $reportVersion overrides it. */
  public ?int $version;

  /**
   * 'v1', 'v2', 'v3' or 'latest'; lowercase and case-sensitive ('V3' and 'Latest' are rejected). Overrides
   * $version. Used only when $create is true. null falls back to $version if set, else the server default.
   */
  public ?string $reportVersion;
  
  public ?ReportCustomizationColors $colors;

  /**
   * @param bool $create
   * @param string $title
   * @param string $largeLogo
   * @param bool $rtl
   * @param PdfReportVersion $version
   * @param ReportCustomizationColors $colors
   * @param string $reportVersion
   */
  public function __construct(
    bool $create,
    ?string $title,
    ?string $largeLogo,
    bool $rtl,
    ?int $version = null,
    ?ReportCustomizationColors $colors = null,
    ?string $reportVersion = null
  ) {
    $this->create = $create;
    $this->title = $title;
    $this->largeLogo = $largeLogo;
    $this->rtl = $rtl; 
    $this->version = $version; 
    $this->colors = $colors; 
    $this->reportVersion = $reportVersion;
  }
}
