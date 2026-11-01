<?php

namespace Copyleaks;
class ModerationsModel
{
    /** Text: labelled segments of the submitted text, one position per segment in the inner arrays. */
    public $text;

    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    public static function fromArray(?array $data)
    {      
        $text = Text::fromArray($data['text']);

        return new self($text);
    }
}

