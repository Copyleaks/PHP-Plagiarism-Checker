<?php

namespace Copyleaks;

class Metadata
{
 /**
     * @var string|null The submitted URL after all HTTP redirects.
     */
    public ?string $finalUrl;

    /**
     * @var string|null Extracted canonical URL from the scanned document.
     */
    public ?string $canonicalUrl;

    /**
     * @var string|null Publication date of the scanned document (e.g., in ISO 8601 format).
     */
    public ?string $publishDate;

    /**
     * @var string|null Creation date of the scanned document (e.g., in ISO 8601 format).
     */
    public ?string $creationDate;

    /**
     * @var string|null Last modification date of the scanned document (e.g., in ISO 8601 format).
     */
    public ?string $lastModificationDate;

    /**
     * @var string|null Scanned document author.
     */
    public ?string $author;

    /**
     * @var string|null Scanned document organization.
     */
    public ?string $organization;

    /**
     * @var string|null Scanned document filename.
     */
    public ?string $filename;

    public function __construct(
        ?string $finalUrl = null,
        ?string $canonicalUrl = null,
        ?string $publishDate = null,
        ?string $creationDate = null,
        ?string $lastModificationDate = null,
        ?string $author = null,
        ?string $organization = null,
        ?string $filename = null
    ) {
        $this->finalUrl = $finalUrl;
        $this->canonicalUrl = $canonicalUrl;
        $this->publishDate = $publishDate;
        $this->creationDate = $creationDate;
        $this->lastModificationDate = $lastModificationDate;
        $this->author = $author;
        $this->organization = $organization;
        $this->filename = $filename;
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
            $data['filename'] ?? null
        );
    }
}
