<?php
namespace Copyleaks;
use DateTime;
class TextModerationScannedDocument
{
    public $scanId;
    public $totalWords;
    public $totalExcluded;
    public $actualCredits;
    public $expectedCredits;
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
     * @throws InvalidArgumentException if required fields are missing.
     */
    public static function fromArray(array $data)
    {
        // Check for required fields
        if (!isset($data['scanId'])) {
            throw new InvalidArgumentException("The 'scanId' field is required.");
        }

        if (!isset($data['totalWords'])) {
            throw new InvalidArgumentException("The 'totalWords' field is required.");
        }

        if (!isset($data['totalExcluded'])) {
            throw new InvalidArgumentException("The 'totalExcluded' field is required.");
        }

        if (!isset($data['actualCredits'])) {
            throw new InvalidArgumentException("The 'actualCredits' field is required.");
        }

        if (!isset($data['expectedCredits'])) {
            throw new InvalidArgumentException("The 'expectedCredits' field is required.");
        }

        if (!isset($data['creationTime'])) {
            throw new InvalidArgumentException("The 'creationTime' field is required.");
        }

        
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
