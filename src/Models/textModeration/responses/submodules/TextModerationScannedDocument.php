<?php
namespace Copyleaks;
use DateTime;
class TextModerationScannedDocument
{
    public $scanId;

    public $totalWords;

    /** Number of excluded words. */
    public $totalExcluded;

    /** Credits used. */
    public $actualCredits;

    public $expectedCredits;

    /** DateTime, or null. */
    public $creationTime;

    /**
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
     * @param array $data
     * @return TextModerationScannedDocument
     */
    public static function fromArray(?array $data)
    {
        $creationTime = is_string($data['creationTime'] ?? null) ? new DateTime($data['creationTime']) : null;

        return new self(
            $data['scanId'] ?? null,
            $data['totalWords'] ?? null,
            $data['totalExcluded'] ?? null,
            $data['actualCredits'] ?? null,
            $data['expectedCredits'] ?? null,
            $creationTime
        );
    }
}
