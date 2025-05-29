<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class RepositoryMetadata extends Metadata
{
    public ?string $submittedBy;

    public function __construct(?string $submittedBy = null)
    {
        $this->submittedBy = $submittedBy;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['submittedBy'] ?? null
        );
    }
}
