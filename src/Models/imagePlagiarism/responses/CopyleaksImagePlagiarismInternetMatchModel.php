<?php

namespace Copyleaks;

/**
 * A copy of the image found on the web.
 */
class CopyleaksImagePlagiarismInternetMatchModel
{
    /** URL of the matching image. */
    public ?string $url;

    /** 0 = full match, 1 = partial match. See CopyleaksImagePlagiarismMatchTypes. */
    public ?int $matchType;

    /**
     * The web pages where the matching image was found. Empty when none were returned.
     * @var CopyleaksImagePlagiarismWebPageModel[]
     */
    public array $webPages;

    public function __construct(?string $url = null, ?int $matchType = null, array $webPages = [])
    {
        $this->url = $url;
        $this->matchType = $matchType;
        $this->webPages = $webPages;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $webPages = [];
        if (is_array($data['webPages'] ?? null)) {
            foreach ($data['webPages'] as $page) {
                if (is_array($page)) {
                    $webPages[] = CopyleaksImagePlagiarismWebPageModel::fromArray($page);
                }
            }
        }

        return new self(
            is_string($data['url'] ?? null) ? $data['url'] : null,
            is_numeric($data['matchType'] ?? null) ? (int) $data['matchType'] : null,
            $webPages
        );
    }
}
