<?php

namespace Copyleaks;

/**
 * A web page where a matching image was found.
 */
class CopyleaksImagePlagiarismWebPageModel
{
    public ?string $url;

    public function __construct(?string $url = null)
    {
        $this->url = $url;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        return new self(is_string($data['url'] ?? null) ? $data['url'] : null);
    }
}
