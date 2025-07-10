<?php

namespace Copyleaks;
class TextModerationChars
{
    /**
     * Start character position of the labelled segment.
     */
    public $labels;

    /**
     * Predicted label index for the corresponding segment. The index can be resolved to its ID using the supplied legend.
     */
    public $starts;

    /**
     * Labelled segment character length.
     */
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
     */
    public static function fromArray(?array $data)
    {
        return new self($data['labels'], $data['starts'], $data['lengths']);
    }
}
