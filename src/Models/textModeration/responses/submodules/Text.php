<?php

namespace Copyleaks;
class Text
{
    public $chars;

    /**
     * Constructor to initialize properties.
     *
     * @param TextModerationChars $chars
     */
    public function __construct(TextModerationChars $chars)
    {
        $this->chars = $chars;
    }

    /**
     * Create an instance of Text from an array.
     *
     * This method expects an array with a sub-array for 'chars', which will be used to create an instance
     * of TextModerationChars.
     *
     * @param array $data
     * @return Text
     */
    public static function fromArray(array $data)
    {
        // Check for required fields
        if (!isset($data['chars']) || !is_array($data['chars'])) {
            return new self(null);
        }

        $chars = TextModerationChars::fromArray($data['chars']);

        return new self($chars);
    }
}
