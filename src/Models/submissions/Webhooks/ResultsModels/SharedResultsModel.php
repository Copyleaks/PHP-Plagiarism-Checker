<?php


namespace Copyleaks;
use Copyleaks\Metadata;
/**
 * Represents results obtained from an internet source during a scan.
 * Properties are public, nullable, and initialized via the constructor.
 */
class SharedResultsModel
{
    /**
     * A unique identifier assigned to this specific internet result.
     * @var string|null
     */
    public ?string $id;

    /**
     * The title of the webpage or document found.
     * @var string|null
     */
    public ?string $title;

    /**
     * An introductory snippet, abstract, or summary from the source.
     * @var string|null
     */
    public ?string $introduction;

    /**
     * The count of words that matched the scan criteria within this source.
     * @var int|null
     */
    public ?int $matchedWords;

    /**
     * An identifier linking this result back to the specific scan job.
     * @var string|null
     */
    public ?string $scanId;

    /**
     * An object containing metadata related to the internet source.
     * Requires the 'Metadata' class definition to be available.
     * @var Metadata|null
     */
    public ?Metadata $metadata; // Property type-hinted as the Metadata class

    // The 'url' property has been removed as per the provided Java fields.

    /**
     * Constructor to initialize the internet result properties.
     * All parameters are optional and default to null.
     *
     * @param string|null $id Unique ID for the result.
     * @param string|null $title Title of the source.
     * @param string|null $introduction Introduction or snippet.
     * @param int|null $matchedWords Count of matched words.
     * @param string|null $scanId Identifier of the scan job.
     * @param Metadata|null $metadata An instance of the Metadata class (or null).
     */
    public function __construct(
        ?string $id = null,
        ?string $title = null,
        ?string $introduction = null,
        ?int $matchedWords = null,
        ?string $scanId = null,
        ?Metadata $metadata = null // Accepts an instance of Metadata or null
        // The 'url' parameter has been removed.
    ) {
        $this->id = $id;
        $this->title = $title;
        $this->introduction = $introduction;
        $this->matchedWords = $matchedWords;
        $this->scanId = $scanId;
        $this->metadata = $metadata;
        // The assignment for 'url' has been removed.
    }

    // No explicit getter methods are defined
    // because all properties are public.
}