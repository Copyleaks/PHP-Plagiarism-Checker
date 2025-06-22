<?php

namespace Copyleaks;
class TextModerationChars
{
    public $labels;
    public $starts;
    public $lengths;

    /**
     * Constructor to initialize properties.
     *
     * @param array $labels
     * @param array $starts
     * @param array $lengths
     */
    public function __construct(array $labels, array $starts, array $lengths)
    {
        $this->labels = $labels;
        $this->starts = $starts;
        $this->lengths = $lengths;
    }

    /**
     * Create an instance of TextModerationChars from an array.
     *
     * @param array $data
     * @return TextModerationChars
     * @throws InvalidArgumentException if required fields are missing or not arrays.
     */
    public static function fromArray(array $data)
    {
        // Check for required fields
        if (!isset($data['labels']) || !is_array($data['labels'])) {
            throw new InvalidArgumentException("The 'labels' field is required and must be an array.");
        }

        if (!isset($data['starts']) || !is_array($data['starts'])) {
            throw new InvalidArgumentException("The 'starts' field is required and must be an array.");
        }

        if (!isset($data['lengths']) || !is_array($data['lengths'])) {
            throw new InvalidArgumentException("The 'lengths' field is required and must be an array.");
        }

        return new self($data['labels'], $data['starts'], $data['lengths']);
    }
}
