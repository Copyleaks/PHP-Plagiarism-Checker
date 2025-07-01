<?php

namespace Copyleaks;

class Results
{
    public ?array $database;
    public ?array $batch;
    public ?array $repositories;
    public ?Score $score;
    public ?array $internet;

    public function __construct(
        ?array $database = null,
        ?array $batch = null,
        ?array $repositories = null,
        ?Score $score = null,
        ?array $internet = null
    ) {
        $this->database = $database;
        $this->batch = $batch;
        $this->repositories = $repositories;
        $this->score = $score;
        $this->internet = $internet;
    }

    public static function fromArray(array $data): self
    {
        $database = isset($data['database']) && is_array($data['database'])
            ? array_map(fn($item) => Database::fromArray($item), $data['database'])
            : null;

        $batch = isset($data['batch']) && is_array($data['batch'])
            ? array_map(fn($item) => Batch::fromArray($item), $data['batch'])
            : null;

        $repositories = isset($data['repositories']) && is_array($data['repositories'])
            ? array_map(fn($item) => Repositories::fromArray($item), $data['repositories'])
            : null;

         $score = isset($data['score']) 
            ? Score::fromArray($data['score'])
            : null;

        $internet = isset($data['internet']) && is_array($data['internet'])
            ? array_map(fn($item) => Internet::fromArray($item), $data['internet'])
            : null;

        return new self($database, $batch, $repositories, $score, $internet);
    }
}
