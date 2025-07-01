<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;
use Copyleaks\Metadata;

class Repositories extends SharedResultsModel
{
/**
     * @var string|null The repository ID that has the result.
     */
    public ?string $repositoryId;

    /**
     * @var Tags[]|null Tags object array. Each element in the array is an instance of the Tags class.
     */
    public ?array $tags;

    /**
     * @var Metadata|null Metadata object associated with the repository result.
     */
    public ?Metadata $metadata;

    public function __construct(
        ?string $repositoryId = null,
        ?array $tags = null,
        ?Metadata $metadata = null
    ) {
        $this->repositoryId = $repositoryId;
        $this->tags = $tags;
        $this->metadata = $metadata;
    }

    public static function fromArray(array $data): self
    {
        $repositoryId = $data['repositoryId'] ?? null;

        $tags = $data['tags'] ?? [];

        $metadata = null;
        if (isset($data['metadata']) && is_array($data['metadata'])) {
            $metadata = new RepositoryMetadata($data['metadata']);
        }

        return new self($repositoryId, $tags, $metadata);
    }
}
