<?php

namespace Copyleaks;
use Copyleaks\SharedResultsModel;

class Batch extends SharedResultsModel
{

    public ?array $tags;

    public function __construct(
        ?array $tags = []
    ) {
        $this->tags = $tags;
    }

}
