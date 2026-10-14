<?php

namespace Copyleaks;

/**
 * A result of Internal AI Source Match: content that matches a source in the Copyleaks internal AI database.
 */
class InternalAIData
{
    /**
     * @var string|null Short identifier of the match.
     */
    public ?string $id;

    /**
     * @var string|null Title of the matched database entry.
     */
    public ?string $title;

    /**
     * @var string|null Sample excerpt of the matched content.
     */
    public ?string $introduction;

    /**
     * @var int|null Total number of words matched from the Copyleaks internal AI database.
     */
    public ?int $matchedWords;

    /**
     * @var int|null Number of exactly identical words.
     */
    public ?int $identicalWords;

    /**
     * @var int|null Number of similar but not identical words.
     */
    public ?int $similarWords;

    /**
     * @var int|null Number of paraphrased words.
     */
    public ?int $paraphrasedWords;

    /**
     * @var int|null Total word count of the matched database entry.
     */
    public ?int $totalWords;

    /**
     * @var Tags[] Result tags, for example the "internal-ai-source-match" tag.
     */
    public array $tags;

    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $introduction = null,
        ?int $matchedWords = null,
        ?int $identicalWords = null,
        ?int $similarWords = null,
        ?int $paraphrasedWords = null,
        ?int $totalWords = null,
        array $tags = []
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->introduction = $introduction;
        $this->matchedWords = $matchedWords;
        $this->identicalWords = $identicalWords;
        $this->similarWords = $similarWords;
        $this->paraphrasedWords = $paraphrasedWords;
        $this->totalWords = $totalWords;
        $this->tags = $tags;
    }

    public static function fromArray(array $data): self
    {
        $int = static function ($value): ?int {
            return is_numeric($value) ? (int) $value : null;
        };
        $string = static function ($value): ?string {
            return is_scalar($value) ? (string) $value : null;
        };
        $tags = [];
        if (is_array($data['tags'] ?? null)) {
            foreach ($data['tags'] as $tag) {
                if (is_array($tag)) {
                    $tags[] = Tags::fromArray($tag);
                }
            }
        }

        return new self(
            $string($data['id'] ?? null),
            $string($data['title'] ?? null),
            $string($data['introduction'] ?? null),
            $int($data['matchedWords'] ?? null),
            $int($data['identicalWords'] ?? null),
            $int($data['similarWords'] ?? null),
            $int($data['paraphrasedWords'] ?? null),
            $int($data['totalWords'] ?? null),
            $tags
        );
    }

    /**
     * Builds a list of InternalAIData from the decoded internalAIData array. Items that are not objects are skipped.
     * @return InternalAIData[]|null null when $items is not an array.
     */
    public static function listFromArray($items): ?array
    {
        if (!is_array($items)) {
            return null;
        }
        $list = [];
        foreach ($items as $item) {
            if (is_array($item)) {
                $list[] = self::fromArray($item);
            }
        }
        return $list;
    }
}
