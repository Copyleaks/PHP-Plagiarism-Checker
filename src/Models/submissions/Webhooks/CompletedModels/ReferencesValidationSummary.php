<?php

namespace Copyleaks;

/**
 * Counts of the references that the Citation Checker detected and validated.
 */
class ReferencesValidationSummary
{
    /** @var int|null References detected. */
    public ?int $total;

    /** @var int|null Detected references identified as academic. */
    public ?int $academic;

    /** @var int|null Academic references fully corroborated against the Copyleaks academic citation index. */
    public ?int $academicValidated;

    /** @var int|null Non-academic references fully corroborated against their cited source. */
    public ?int $nonAcademicValidated;

    public function __construct(?int $total = null, ?int $academic = null, ?int $academicValidated = null, ?int $nonAcademicValidated = null)
    {
        $this->total = $total;
        $this->academic = $academic;
        $this->academicValidated = $academicValidated;
        $this->nonAcademicValidated = $nonAcademicValidated;
    }

    public static function fromArray(array $data): self
    {
        $int = static function ($value): ?int {
            return is_numeric($value) ? (int) $value : null;
        };
        return new self(
            $int($data['total'] ?? null),
            $int($data['academic'] ?? null),
            $int($data['academicValidated'] ?? null),
            $int($data['nonAcademicValidated'] ?? null)
        );
    }
}
