<?php

namespace Copyleaks;

class Results
{
    public ?array $database;
    public ?array $batch;
    public ?array $repositories;
    public ?array $score;
    public ?array $internet;

    public function __construct(
        ?array $database = null,
        ?array $batch = null,
        ?array $repositories = null,
        ?array $score = null,
        ?array $internet = null
    ) {
        $this->database = $database;
        $this->batch = $batch;
        $this->repositories = $repositories;
        $this->score = $score;
        $this->internet = $internet;
    }

    /**
     * Deserialize from an array, mapping submodules to their classes.
     * Assumes each element in arrays is an associative array to be passed to the submodule constructor.
     */
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

        $score = isset($data['score']) && is_array($data['score'])
            ? array_map(fn($item) => Score::fromArray($item), $data['score'])
            : null;

        $internet = isset($data['internet']) && is_array($data['internet'])
            ? array_map(fn($item) => Internet::fromArray($item), $data['internet'])
            : null;

        return new self($database, $batch, $repositories, $score, $internet);
    }
}
