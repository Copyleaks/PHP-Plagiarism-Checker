<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;

class NewResultsRepositories extends SharedResultsModel
{

    public ?string $repositoryId;

    public function __construct(
        ?array $repositoryId = []
    ) {
        $this->repositoryId = $repositoryId;
    }

}
