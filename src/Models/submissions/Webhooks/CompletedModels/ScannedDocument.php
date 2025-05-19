<?php

namespace Copyleaks;

class ScannedDocument
{
    public ?string $scanId;
    public ?string $creationTime;

    public ?int $totalWords;
    public ?int $totalExcluded;
    public ?int $credits;
public ?Metadata $metadata;

public function __construct(?string $scanId, ?string $creationTime, ?int $totalWords, ?int $totalExcluded, ?int $credits, ?Metadata $metadata)  
{   
    $this->scanId = $scanId;
    $this->creationTime = $creationTime;
    $this->totalWords = $totalWords;
    $this->totalExcluded = $totalExcluded;
    $this->credits = $credits;
    $this->metadata = $metadata;

}
}

