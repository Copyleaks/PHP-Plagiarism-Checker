<?php
namespace Copyleaks;


use Copyleaks\ModerationsModel;
use Copyleaks\TextModerationsLegend;
use Copyleaks\TextModerationScannedDocument;

class CopyleaksTextModerationResponseModel
{
    /** ModerationsModel: the labelled segments of the text. */
    public $moderations;

    /** TextModerationsLegend[]: maps the label indices in text.chars.labels to label IDs. */
    public $legend;

    /** TextModerationScannedDocument. */
    public $scannedDocument;

    /** Version of the moderation model used; null when the response does not include it. */
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
       $moderations = ModerationsModel::fromArray($data['moderations']);
        
        $legend = array_map(function($legendData) {
            return TextModerationsLegend::fromArray($legendData);
        }, is_array($data['legend'] ?? null) ? $data['legend'] : []);

        $scannedDocument = TextModerationScannedDocument::fromArray($data['scannedDocument']);

        $modelVersion = is_scalar($data['modelVersion'] ?? null) ? (string) $data['modelVersion'] : null;

        return new self($moderations, $legend, $scannedDocument, $modelVersion);
    }
}
