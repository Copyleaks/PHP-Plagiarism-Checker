<?php

namespace Copyleaks;

/**
 * Image size in pixels.
 */
class CopyleaksImageShapeModel
{
    public int $height;

    public int $width;

    public function __construct(int $height, int $width)
    {
        $this->height = $height;
        $this->width = $width;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(
            $data['height'] ?? 0,
            $data['width'] ?? 0
        );
    }
}
