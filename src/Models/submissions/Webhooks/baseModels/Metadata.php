<?php

namespace Copyleaks;

class Metadata
{
    public ?string $finalUrl;
    public ?string $canonicalUrl;
    public ?string $publishDate;
    public ?string $creationDate;
    public ?string $lastModificationDate;
    public ?string $author;
    public ?string $organization;
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
