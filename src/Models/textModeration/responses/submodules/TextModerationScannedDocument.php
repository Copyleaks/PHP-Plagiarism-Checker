<?php
namespace Copyleaks;
use DateTime;
class TextModerationScannedDocument
{
    /** The scan id given by the user. */
    public $scanId;

    /** Total number of words found in the scanned text. */
    public $totalWords;

    /** Total excluded words from the text. */
    public $totalExcluded;

    /** The cost of credits for this scan. */
    public $actualCredits;

    /** The amount of credits that was expected to be spent on the scan. */
    public $expectedCredits;

    /** Creation time of the scan. */
    public $creationTime;

    /**
     * Constructor to initialize properties.
     *
     * @param string $scanId
     * @param int $totalWords
     * @param int $totalExcluded
     * @param int $actualCredits
     * @param int $expectedCredits
     * @param DateTime $creationTime
     */
    public function __construct($scanId, $totalWords, $totalExcluded, $actualCredits, $expectedCredits, $creationTime)
    {
        $this->scanId = $scanId;
        $this->totalWords = $totalWords;
        $this->totalExcluded = $totalExcluded;
        $this->actualCredits = $actualCredits;
        $this->expectedCredits = $expectedCredits;
        $this->creationTime = $creationTime;
    }

    /**
     * Create an instance of TextModerationScannedDocument from an array.
     *
     * @param array $data
     * @return TextModerationScannedDocument
     */
    public static function fromArray(?array $data)
    {
        $creationTime = new DateTime($data['creationTime']);

        return new self(
            $data['scanId'],
            $data['totalWords'],
            $data['totalExcluded'],
            $data['actualCredits'],
            $data['expectedCredits'],
            $creationTime
        );
    }
}
