<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class RepositoryMetadata extends Metadata
{
    /**
     * @var string|null Email of the user which added this document to the repository.
     */
    public ?string $submittedBy;

    public function __construct(
        ?string $finalUrl = null,
        ?string $canonicalUrl = null,
        ?string $publishDate = null,
        ?string $creationDate = null,
        ?string $lastModificationDate = null,
        ?string $author = null,
        ?string $organization = null,
        ?string $filename = null,
        ?string $submittedBy = null 
    ) {

        parent::__construct(
            $finalUrl,
            $canonicalUrl,
            $publishDate,
            $creationDate,
            $lastModificationDate,
            $author,
            $organization,
            $filename
        );
        $this->submittedBy = $submittedBy;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['finalUrl'] ?? null,
            $data['canonicalUrl'] ?? null,
            $data['publishDate'] ?? null,
            $data['creationDate'] ?? null,
            $data['lastModificationDate'] ?? null,
            $data['author'] ?? null,
            $data['organization'] ?? null,
            $data['filename'] ?? null,
            $data['submittedBy'] ?? null 
        );
    }

}
