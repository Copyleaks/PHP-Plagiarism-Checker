<?php

namespace Copyleaks;
class TextModerationChars
{
    /** Predicted label index of each segment; resolve it to a label ID with the response legend. */
    public $labels;

    /** Start character position of each segment. */
    public $starts;

    /** Character length of each segment. */
    public $lengths;

    /**
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

    /** @param array $data */
    public static function fromArray(?array $data)
    {
        return new self($data['labels'], $data['starts'], $data['lengths']);
    }
}
