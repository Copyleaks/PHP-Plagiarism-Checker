<?php

namespace Copyleaks;
/**
 * Represents metadata object.
 * Properties are public and initialized via the constructor.
 * All properties are nullable strings.
 */
class Metadata
{
    /**
     * The final URL.
     * @var string|null
     */
    public ?string $finalUrl;

    /**
     * The canonical URL.
     * @var string|null
     */
    public ?string $canonicalUrl;

    /**
     * The date the resource was published.
     * @var string|null
     */
    public ?string $publishDate;

    /**
     * The date the resource was initially created.
     * @var string|null
     */
    public ?string $creationDate;

    /**
     * The date the resource was last modified.
     * @var string|null
     */
    public ?string $lastModificationDate;

    /**
     * The identified author of the resource.
     * @var string|null
     */
    public ?string $author;

    /**
     * The publishing organization or site name.
     * @var string|null
     */
    public ?string $organization;

    /**
     * The original filename, if applicable (e.g., for downloaded files).
     * @var string|null
     */
    public ?string $filename;

    /**
     * Constructor to initialize all metadata properties.
     * All parameters are optional and default to null.
     *
     * @param string|null $finalUrl The final URL.
     * @param string|null $canonicalUrl The canonical URL.
     * @param string|null $publishDate The publication date string.
     * @param string|null $creationDate The creation date string.
     * @param string|null $lastModificationDate The last modification date string.
     * @param string|null $author The author's name.
     * @param string|null $organization The organization's name.
     * @param string|null $filename The filename.
     */
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
}
