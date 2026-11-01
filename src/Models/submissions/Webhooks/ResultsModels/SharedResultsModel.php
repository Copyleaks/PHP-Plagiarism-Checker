<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class SharedResultsModel
{
    /** @var string|null */
    public ?string $id;

    /** @var string|null Mostly extracted from the document content. */
    public ?string $title;

    /** @var string|null Brief introduction, mostly extracted from the document content. */
    public ?string $introduction;

    /** @var int|null Words matched with the scanned document. */
    public ?int $matchedWords;

    /**
     * @var string|null Scan ID of the result when it is your own submission in the internal database; empty
     * otherwise.
     */
    public ?string $scanId;

    /** @var Metadata|null */
    public ?Metadata $metadata;

    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $introduction = null,
        ?int $matchedWords = null,
        ?string $scanId = null,
        ?Metadata $metadata = null
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->introduction = $introduction;
        $this->matchedWords = $matchedWords;
        $this->scanId = $scanId;
        $this->metadata = $metadata;
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['id'] ?? null,
            $data['title'] ?? null,
            $data['introduction'] ?? null,
            $data['matchedWords'] ?? null,
            $data['scanId'] ?? null,
            isset($data['metadata']) ? Metadata::fromArray($data['metadata']) : null
        );
    }
}
