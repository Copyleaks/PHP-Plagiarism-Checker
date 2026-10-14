<?php

namespace Copyleaks;

/**
 * Citation Checker summary of the completed webhook (referencesValidation).
 * Only present when properties.references.validate was enabled for the scan.
 */
class ReferencesValidation
{
    /**
     * @var ReferencesValidationSummary|null Counts of the detected and validated references.
     */
    public ?ReferencesValidationSummary $summary;

    public function __construct(?ReferencesValidationSummary $summary = null)
    {
        $this->summary = $summary;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            is_array($data['summary'] ?? null) ? ReferencesValidationSummary::fromArray($data['summary']) : null
        );
    }
}
