<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;
use Copyleaks\Metadata;

class Repositories extends SharedResultsModel
{
    /** @var string|null Repository that has the result. */
    public ?string $repositoryId;

    /** @var Tags[]|null */
    public ?array $tags;

    /** @var Metadata|null */
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
