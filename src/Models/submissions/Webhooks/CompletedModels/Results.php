<?php

namespace Copyleaks;

class Results
{
    public ?array $database;
    public ?array $batch;
    public ?array $repositories;
    public ?array $score;
    public ?array $internet;


public function __construct(?array $database, ?array $batch, ?array $repositories, ?array $score, ?array $internet){
    $this->database = $database;
    $this->batch = $batch;
    $this->repositories = $repositories;
    $this->score = $score;
    $this->internet = $internet;

    }
}

