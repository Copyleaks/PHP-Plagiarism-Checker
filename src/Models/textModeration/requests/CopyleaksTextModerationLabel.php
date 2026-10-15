<?php

namespace Copyleaks;

class CopyleaksTextModerationLabel
{
    /**
     * See CopyleaksTextModerationConstants.
     * @var string
     */
    public string $id; 

    /** @param string $id */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

}
