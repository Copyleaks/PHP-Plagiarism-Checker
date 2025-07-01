<?php

namespace Copyleaks;

use Copyleaks\Metadata;

class SharedResultsModel
{
    /**
     * @var string|null Unique result ID to identify this result.
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
     * @var string|null In case a result was found in the Copyleaks internal database, and was submitted by you,
     * this will show the scan ID of the specific result. Otherwise, this field will remain empty.
     */
    public ?string $scanId;

    /**
     * @var Metadata|null Metadata object.
     */
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
