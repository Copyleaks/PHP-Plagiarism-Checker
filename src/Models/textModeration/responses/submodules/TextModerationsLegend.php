<?php
namespace Copyleaks;
class TextModerationsLegend
{
    /** Label index, as used in text.chars.labels. */
    public $index;

    /** Label ID, see CopyleaksTextModerationConstants. */
    public $id;

    /**
     * @param int $index
     * @param string $id
     */
    public function __construct($index, $id)
    {
        $this->index = $index;
        $this->id = $id;
    }

    /**
     * @param array $data
     * @return TextModerationsLegend
     * @throws InvalidArgumentException if required fields are missing.
     */
    public static function fromArray(?array $data)
    {
        return new self($data['index'], $data['id']);
    }
}
