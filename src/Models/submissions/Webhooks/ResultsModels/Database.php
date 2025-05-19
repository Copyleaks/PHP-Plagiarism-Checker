<?php

namespace Copyleaks;

use Copyleaks\SharedResultsModel;
class Database extends SharedResultsModel
{

    public ?array $tags;

    public function __construct(
        ?array $tags = []
    ) {
        $this->tags = $tags;
    }

}
