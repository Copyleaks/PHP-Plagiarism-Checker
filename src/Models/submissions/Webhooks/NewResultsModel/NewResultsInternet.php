<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class NewResultsInternet
{
 /**
     * @var string|null Unique result ID to identify the result.
     */
    public ?string $id;

    /**
     * @var string|null Document title. Mostly extracted from the document content.
     */
    public ?string $title;

    /**
     * @var string|null Document brief introduction. Mostly extracted from the document content.
     */
    public ?string $introduction;

    /**
     * @var int|null Total matched words between this result and the scanned document.
     */
    public ?int $matchedWords;

    /**
     * @var Metadata|null Metadata object associated with this result.
     */
    public ?Metadata $metadata;

    /**
     * @var string|null Public URL of the resource.
     */
    public ?string $url;


    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $introduction = null,
        ?int $matchedWords = null,
        ?Metadata $metadata = null,
        ?string $url = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->introduction = $introduction;
        $this->matchedWords = $matchedWords;
        $this->metadata = $metadata;
        $this->url = $url;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? null,
            $data['introduction'] ?? null,
            isset($data['matchedWords']) ? (int)$data['matchedWords'] : null,
            isset($data['metadata']) && is_array($data['metadata']) ? Metadata::fromArray($data['metadata']) : null,
            $data['url'] ?? null
        );
    }
}
