<?php

namespace Copyleaks;

class Alerts
{
    /** @var int|null */
    public ?int $category;

    /** @var string|null Unique per scan alert, see CopyleaksAlertCodes. */
    public ?string $code;

    /** @var string|null */
    public ?string $title;

    /** @var string|null Human-readable details. */
    public ?string $message;

    /** @var string|null URL of a page about this alert. */
    public ?string $helpLink;

    /** @var int|null 0 (very low) to 4 (very high). */
    public ?int $severity;

    /** @var string|null A JSON string. */
    public ?string $additionalData;

    public function __construct(
        ?string $category = null,
        ?string $code = null,
        ?string $title = null,
        ?string $message = null,
        ?string $helpLink = null,
        ?string $severity = null,
        ?string $additionalData = null
    ) {
        $this->category = $category;
        $this->code = $code;
        $this->title = $title;
        $this->message = $message;
        $this->helpLink = $helpLink;
        $this->severity = $severity;
        $this->additionalData = $additionalData;
    }

    public static function fromArray(array $data): Alerts
    {
        return new self(
            $data['category'] ?? null,
            $data['code'] ?? null,
            $data['title'] ?? null,
            $data['message'] ?? null,
            $data['helpLink'] ?? null,
            $data['severity'] ?? null,
            $data['additionalData'] ?? null
        );
    }

    /**
     * Decode the AI text detection result in additionalData, after trimming trailing NUL and ASCII whitespace.
     * Returns null when the code is not exactly 'suspected-ai-text', when additionalData is null or blank, or when
     * it is valid JSON but not an object. Fields of an unexpected type are read as missing.
     * @return CopyleaksAiTextDetectionResponseModel|null
     * @throws \JsonException When additionalData is not valid JSON or is nested deeper than 512 levels.
     */
    public function getAIDetectionResult(): ?CopyleaksAiTextDetectionResponseModel
    {
        if ($this->code !== CopyleaksAlertCodes::SUSPECTED_AI_TEXT || $this->additionalData === null) {
            return null;
        }

        // Explicit ASCII list: one linear pass, no regex, not Unicode-aware.
        $json = rtrim($this->additionalData, "\0\t\n\v\f\r ");
        if ($json === '') {
            return null;
        }

        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        // json_decode turns both {} and [] into a PHP array, so check that the JSON text itself is an object.
        // Valid JSON can only start with JSON whitespace (space, \t, \n, \r) before its first value.
        if (!is_array($data) || $json[strspn($json, " \t\n\r")] !== '{') {
            return null;
        }

        return CopyleaksAiTextDetectionResponseModel::fromArray($data);
    }
}
