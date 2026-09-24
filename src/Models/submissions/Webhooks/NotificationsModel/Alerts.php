<?php

namespace Copyleaks;

class Alerts
{
    /**
     * @var int|null Scan alert category.
     * Note: Changed to int|null as per C# 'int Category' but your PHP request used '?string $category'.
     * Assuming 'category' is an integer code. If it's truly a string, adjust type accordingly.
     */
    public ?int $category;

    /**
     * @var string|null Scan alert code. The code is unique for each scan alert.
     */
    public ?string $code;

    /**
     * @var string|null Scan alert human-readable title.
     */
    public ?string $title;

    /**
     * @var string|null Provides human-readable information about the scan alert.
     */
    public ?string $message;

    /**
     * @var string|null URL to a resource describing the specific scan alert.
     */
    public ?string $helpLink;

    /**
     * @var int|null Specifies the importance of the scan alert.
     * Note: Changed to int|null as per C# 'int Severity' but your PHP request used '?string $severity'.
     * Assuming 'severity' is an integer code. If it's truly a string, adjust type accordingly.
     */
    public ?int $severity;

    /**
     * @var string|null Additional data about the scan alert. Supplied as a JSON string.
     */
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
     * Decodes the AI text detection result carried by this alert.
     * Only the 'suspected-ai-text' alert (CopyleaksAlertCodes::SUSPECTED_AI_TEXT, category 2, severity 4)
     * carries it: its additionalData is the result encoded as a JSON string.
     * Trailing NUL (\0) and ASCII whitespace (\t \n \v \f \r and space) characters are removed before decoding.
     * The raw string stays available in $additionalData.
     *
     * Returns null when:
     * - the alert code is not 'suspected-ai-text' (the match is exact and case-sensitive);
     * - additionalData is null, empty, or only NUL and ASCII whitespace characters;
     * - additionalData is valid JSON but not a JSON object (an array, a number, a string, true, false or null).
     * Otherwise it returns the decoded model. Fields with an unexpected type are read like missing fields,
     * so a valid JSON object never throws (see CopyleaksAiTextDetectionResponseModel::fromArray()).
     *
     * @return CopyleaksAiTextDetectionResponseModel|null the decoded result, or null in the cases listed above.
     * @throws \JsonException when the trimmed additionalData is not valid JSON, or is nested deeper than 512 levels.
     * This is the only exception it throws.
     */
    public function getAIDetectionResult(): ?CopyleaksAiTextDetectionResponseModel
    {
        if ($this->code !== CopyleaksAlertCodes::SUSPECTED_AI_TEXT || $this->additionalData === null) {
            return null;
        }

        // rtrim with an explicit ASCII character list: one linear pass, no regex, not Unicode-aware.
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
