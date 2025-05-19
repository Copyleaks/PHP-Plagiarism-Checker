<?php

namespace Copyleaks;

class Tags
{

    public ?string $code;

    public ?string $title;


    public ?string $description;

    public function __construct(
        ?string $code = null,
        ?string $title = null,
        ?string $description = null
    ) {
        $this->code = $code;
        $this->title = $title;
        $this->description = $title;
    }

}
