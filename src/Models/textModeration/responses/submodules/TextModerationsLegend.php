<?php
namespace Copyleaks;
class TextModerationsLegend
{
    public $index;
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
    public static function fromArray(array $data)
    {
        
        if (!isset($data['index'])) {
            throw new InvalidArgumentException("The 'index' field is required.");
        }

        if (!isset($data['id'])) {
            throw new InvalidArgumentException("The 'id' field is required.");
        }

        return new self($data['index'], $data['id']);
    }
}
