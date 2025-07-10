<?php
namespace Copyleaks;
class TextModerationsLegend
{
    /** The numerical index of the label. */
    public $index;

    /** A unique string identifier for the label. This ID serves as a machine-readable way to identify the label type. */
    public $id;

    /**
     * Constructor to initialize properties.
     *
     * @param int $index
     * @param string $id
     */
    public function __construct($index, $id)
    {
        $this->index = $index;
        $this->id = $id;
    }

    /**
     * Create an instance of TextModerationsLegend from an array.
     *
     * @param array $data
     * @return TextModerationsLegend
     * @throws InvalidArgumentException if required fields are missing.
     */
    public static function fromArray(?array $data)
    {
        return new self($data['index'], $data['id']);
    }
}
