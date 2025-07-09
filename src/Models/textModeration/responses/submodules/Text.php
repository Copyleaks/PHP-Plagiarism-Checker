<?php

namespace Copyleaks;
class Text
{
    /**
     * An object that groups together several arrays detailing the properties of labelled segments.
     */
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
    public static function fromArray(?array $data)
    {
        $chars = TextModerationChars::fromArray($data['chars']);

        return new self($chars);
    }
}
