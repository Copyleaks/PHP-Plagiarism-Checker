<?php
namespace Copyleaks;


use Copyleaks\ModerationsModel;
use Copyleaks\TextModerationsLegend;
use Copyleaks\TextModerationScannedDocument;

class CopyleaksTextModerationResponseModel
{
    public $moderations;

    public $legend;

    public $scannedDocument;

    public function __construct(ModerationsModel $moderations, array $legend, TextModerationScannedDocument $scannedDocument)
    {
        $this->moderations = $moderations;
        $this->legend = $legend;
        $this->scannedDocument = $scannedDocument;
    }

    public static function fromArray(array $data)
    {
        
        if (!isset($data['moderations']) || !is_array($data['moderations'])) {
            return null;
        }

        if (!isset($data['legend']) || !is_array($data['legend'])) {
            return null;        
        }

       if (!isset($data['scannedDocument']) || !is_array($data['scannedDocument'])) {
             return null;
        }

        // Create instances using fromArray methods
       $moderations = ModerationsModel::fromArray($data['moderations']);
        
        // Create array of TextModerationsLegend objects
        $legend = array_map(function($legendData) {
            return TextModerationsLegend::fromArray($legendData);
        }, $data['legend']);

        $scannedDocument = TextModerationScannedDocument::fromArray($data['scannedDocument']);

        return new self($moderations, $legend, $scannedDocument);
    }
}
