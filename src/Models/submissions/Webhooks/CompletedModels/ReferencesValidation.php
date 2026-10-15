<?php

namespace Copyleaks;

class ReferencesValidation
{
    /** @var ReferencesValidationSummary|null */
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
