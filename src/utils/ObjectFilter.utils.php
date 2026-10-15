<?php

namespace Copyleaks;

class  ObjectFilter
{
    public static function filterNullProperties($object)
    {
        foreach ($object as $property => $value) {
            if (is_object($value) || is_array($value)) {
                self::filterNullProperties($value);
            }

            if ($value === null) {
                unset($object->$property);
            }
        }
    }
}
