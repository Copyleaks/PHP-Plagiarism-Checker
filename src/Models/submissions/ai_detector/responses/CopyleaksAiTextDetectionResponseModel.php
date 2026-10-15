<?php

namespace Copyleaks;

use Copyleaks\CopyleaksAiTextDetectionResultModel;
use Copyleaks\CopyleaksAiTextDetectionSummaryModel;
use Copyleaks\CopyleaksAiTextDetectionExplainModel;

/**
 * AI text detection result. In the completed webhook it is the decoded additionalData of the
 * 'suspected-ai-text' alert, see Alerts::getAIDetectionResult().
 */
class CopyleaksAiTextDetectionResponseModel
{
    /** Version of the model used. */
    public ?string $modelVersion;

    /**
     * Segments classified as human or AI. Items that are not JSON objects are skipped.
     * @var CopyleaksAiTextDetectionResultModel[]
     */
    public array $results;

    public ?CopyleaksAiTextDetectionSummaryModel $summary;

    /** Provider of the machine translation to English before detection; 0 = no translation. */
    public ?int $translationProvider;

    /** The translated text, when the text was machine translated to English before detection. */
    public ?string $translation;

    /** Only when AI Logic (explain) was enabled for the scan. */
    public ?CopyleaksAiTextDetectionExplainModel $explain;

    /** null when missing (the completed webhook's AI alert does not include it). */
    public ?CopyleaksAiTextDetectionScannedDocumentModel $scannedDocument;

    public function __construct(
        ?string $modelVersion = null,
        array $results = [],
        ?CopyleaksAiTextDetectionSummaryModel $summary = null,
        ?int $translationProvider = null,
        ?string $translation = null,
        ?CopyleaksAiTextDetectionExplainModel $explain = null,
        ?CopyleaksAiTextDetectionScannedDocumentModel $scannedDocument = null
    ) {
        $this->modelVersion = $modelVersion;
        $this->results = $results;
        $this->summary = $summary;
        $this->translationProvider = $translationProvider;
        $this->translation = $translation;
        $this->explain = $explain;
        $this->scannedDocument = $scannedDocument;
    }

    /**
     * Unknown keys are ignored. A value of an unexpected type is read as missing (null, or [] for lists) and numbers
     * are cast to the property type, so this never throws a TypeError.
     * @param array|null $data Decoded JSON object.
     * @return CopyleaksAiTextDetectionResponseModel|null
     */
    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $results = [];
        if (is_array($data['results'] ?? null)) {
            foreach ($data['results'] as $item) {
                if (is_array($item)) {
                    $results[] = CopyleaksAiTextDetectionResultModel::fromArray($item);
                }
            }
        }

        $translationProvider = $data['translationProvider'] ?? null;

        return new self(
            self::stringValue($data['modelVersion'] ?? null),
            $results,
            is_array($data['summary'] ?? null) ? CopyleaksAiTextDetectionSummaryModel::fromArray($data['summary']) : null,
            is_numeric($translationProvider) ? (int) $translationProvider : null,
            self::stringValue($data['translation'] ?? null),
            is_array($data['explain'] ?? null) ? CopyleaksAiTextDetectionExplainModel::fromArray($data['explain']) : null,
            is_array($data['scannedDocument'] ?? null) ? CopyleaksAiTextDetectionScannedDocumentModel::fromArray($data['scannedDocument']) : null
        );
    }

    private static function stringValue($value): ?string
    {
        return is_string($value) || is_int($value) || is_float($value) ? (string) $value : null;
    }
}
