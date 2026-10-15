<?php

namespace Copyleaks;

class Metadata
{
    /** @var string|null The submitted URL after all redirects. */
    public ?string $finalUrl;

    /** @var string|null Canonical URL found in the document. */
    public ?string $canonicalUrl;

    /** @var string|null E.g. ISO 8601. */
    public ?string $publishDate;

    /** @var string|null E.g. ISO 8601. */
    public ?string $creationDate;

    /** @var string|null E.g. ISO 8601. */
    public ?string $lastModificationDate;

    /** @var string|null */
    public ?string $author;

    /** @var string|null */
    public ?string $organization;

    /** @var string|null */
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
