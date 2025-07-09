<?php

namespace Copyleaks;
class ModerationsModel
{
    /** Moderated text segments corresponding to the submitted text. Each position in the inner arrays corresponds to a single segment in the textual version */
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

