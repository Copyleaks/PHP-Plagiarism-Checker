<?php

namespace Copyleaks;

/**
 * Copies of the image found by the Image Plagiarism Detector.
 */
class CopyleaksImagePlagiarismMatchesModel
{
    /**
     * Copies of the image found on the web.
     * @var CopyleaksImagePlagiarismInternetMatchModel[]
     */
    public array $internet;

    /** Match counts. */
    public ?CopyleaksImagePlagiarismScoreModel $score;

    public function __construct(array $internet = [], ?CopyleaksImagePlagiarismScoreModel $score = null)
    {
        $this->internet = $internet;
        $this->score = $score;
    }

    public static function fromArray(?array $data): ?self
    {
        if (is_null($data)) {
            return null;
        }

        $internet = [];
        if (is_array($data['internet'] ?? null)) {
            foreach ($data['internet'] as $item) {
                if (is_array($item)) {
                    $internet[] = CopyleaksImagePlagiarismInternetMatchModel::fromArray($item);
                }
            }
        }

        return new self(
            $internet,
            is_array($data['score'] ?? null) ? CopyleaksImagePlagiarismScoreModel::fromArray($data['score']) : null
        );
    }
}
