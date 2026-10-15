<?php

namespace Copyleaks;
class Text
{
    /** TextModerationChars: parallel arrays describing the labelled segments. */
    public $chars;

    /** @param TextModerationChars $chars */
    public function __construct(TextModerationChars $chars)
    {
        $this->chars = $chars;
    }

    /**
     * @param array $data Must have a 'chars' array.
     * @return Text
     */
    public static function fromArray(?array $data)
    {
        $chars = TextModerationChars::fromArray($data['chars']);

        return new self($chars);
    }
}
