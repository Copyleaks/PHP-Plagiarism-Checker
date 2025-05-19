<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;

class Repositories extends SharedResultsModel
{

    public ?array $tags;
    public ?RepositoryMetadata $repositoryId;
    public function __construct(
        ?array $tags = [],
        ?RepositoryMetadata $repositoryId
    ) {
        $this->tags = $tags;
        $this->repositoryId = $repositoryId;

    }

}
