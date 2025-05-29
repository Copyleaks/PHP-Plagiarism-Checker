<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;

class NewResultsRepositories extends SharedResultsModel
{
    public ?string $repositoryId;

    public function __construct(
        ?string $repositoryId = null
    ) {
        $this->repositoryId = $repositoryId;
    }

    public static function fromArray(array $data): NewResultsRepositories
    {
        return new self(
            $data['repositoryId'] ?? null
        );
    }
}
