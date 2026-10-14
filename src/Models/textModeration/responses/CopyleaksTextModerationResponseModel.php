<?php
namespace Copyleaks;


use Copyleaks\ModerationsModel;
use Copyleaks\TextModerationsLegend;
use Copyleaks\TextModerationScannedDocument;

class CopyleaksTextModerationResponseModel
{
    /** Moderated text segments detected in the input text. */
    public $moderations;

    /** An array that provides a lookup for the labels referenced by their numerical indices in the text.chars.labels array. Each object within this legend array defines a specific label that was used in the scan. */
    public $legend;

    /** General information about the scanned document. */
    public $scannedDocument;

    /** The version of the moderation model used, or null when the response does not include it. */
    public ?string $modelVersion;

    public function __construct(ModerationsModel $moderations, array $legend, TextModerationScannedDocument $scannedDocument, ?string $modelVersion = null)
    {
        $this->moderations = $moderations;
        $this->legend = $legend;
        $this->scannedDocument = $scannedDocument;
        $this->modelVersion = $modelVersion;
    }

    public static function fromArray(?array $data)
    {
        if(is_null($data))
        throw new \Exception("Response is null!");
        // Create instances using fromArray methods
       $moderations = ModerationsModel::fromArray($data['moderations']);
        
        // Create array of TextModerationsLegend objects
        $legend = array_map(function($legendData) {
            return TextModerationsLegend::fromArray($legendData);
        }, is_array($data['legend'] ?? null) ? $data['legend'] : []);

        $scannedDocument = TextModerationScannedDocument::fromArray($data['scannedDocument']);

        $modelVersion = is_scalar($data['modelVersion'] ?? null) ? (string) $data['modelVersion'] : null;

        return new self($moderations, $legend, $scannedDocument, $modelVersion);
    }
}
