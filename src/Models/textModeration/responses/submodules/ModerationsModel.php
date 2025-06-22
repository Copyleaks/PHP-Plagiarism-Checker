<?php

namespace Copyleaks;
class ModerationsModel
{
    public $text;

    public function __construct(Text $text)
    {
        $this->text = $text;
    }

    public static function fromArray(array $data)
    {
        // Check for required fields
        if (!isset($data['text']) || !is_array($data['text'])) {
            return null;
        }

        
        $text = Text::fromArray($data['text']);

        return new self($text);
    }
}

