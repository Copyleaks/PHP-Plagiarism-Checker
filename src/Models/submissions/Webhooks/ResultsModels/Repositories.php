<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;
use Copyleaks\RepositoryMetadata;

class Repositories extends SharedResultsModel
{
    public ?array $tags;
    public ?RepositoryMetadata $repositoryId;

    public function __construct(
        ?array $tags = [],
        ?RepositoryMetadata $repositoryId = null
    ) {
        $this->tags = $tags;
        $this->repositoryId = $repositoryId;
    }

    public static function fromArray(array $data): self
    {
        $tags = $data['tags'] ?? [];
        $repositoryId = isset($data['repositoryId']) ? RepositoryMetadata::fromArray($data['repositoryId']) : null;

        return new self($tags, $repositoryId);
    }
}
