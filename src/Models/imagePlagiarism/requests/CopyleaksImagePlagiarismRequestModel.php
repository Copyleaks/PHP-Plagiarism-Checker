<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Image Plagiarism Detector request, uploaded as multipart/form-data. Build it from a file with the constructor,
 * or from bytes in memory with fromContent().
 * See https://docs.copyleaks.com/reference/actions/image-plagiarism-detector/check
 */
class CopyleaksImagePlagiarismRequestModel
{
    /**
     * Image file to upload; null when the image comes from $imageContent.
     * Under 20 MB, at most 75 megapixels; JPG, JPEG, PNG, GIF, BMP, WebP or ICO.
     */
    public ?string $imagePath;

    /** Image bytes, used when $imagePath is null (see fromContent()). */
    public ?string $imageContent = null;

    /** File name with extension (.jpg, .jpeg, .png, .gif, .bmp, .webp, .ico), max 255 chars. */
    public string $filename;

    /** Return mock results without using credits. null = server default (false). */
    public ?bool $sandbox;

    /** Returned unchanged in the response, to match the result with your own records. */
    public ?string $developerPayload;

    /**
     * @param string $imagePath
     * @param string $filename
     * @param bool|null $sandbox
     * @param string|null $developerPayload
     */
    public function __construct(string $imagePath, string $filename, ?bool $sandbox = null, ?string $developerPayload = null)
    {
        $this->imagePath = $imagePath;
        $this->filename = $filename;
        $this->sandbox = $sandbox;
        $this->developerPayload = $developerPayload;
    }

    /**
     * Build a request from image bytes in memory.
     * @param string $imageContent
     * @param string $filename
     * @param bool|null $sandbox
     * @param string|null $developerPayload
     */
    public static function fromContent(string $imageContent, string $filename, ?bool $sandbox = null, ?string $developerPayload = null): self
    {
        $model = new self('', $filename, $sandbox, $developerPayload);
        $model->imagePath = null;
        $model->imageContent = $imageContent;
        return $model;
    }

    /**
     * On PHP 8.0 and older, in-memory image bytes are written to a temporary file; the caller deletes it after the
     * request.
     * @param string[] $temporaryFiles Receives the paths of the temporary files.
     * @return array<string, string|\CURLFile>
     * @throws InvalidArgumentException When the image or the filename is missing.
     */
    public function toMultipartFields(array &$temporaryFiles = []): array
    {
        if ($this->filename === '') {
            throw new InvalidArgumentException('The image filename is required.');
        }
        $mimeType = self::mimeType($this->filename);

        if ($this->imagePath !== null && $this->imagePath !== '') {
            if (!is_file($this->imagePath) || !is_readable($this->imagePath)) {
                throw new InvalidArgumentException('The image file cannot be read: ' . $this->imagePath);
            }
            $image = new \CURLFile($this->imagePath, $mimeType, $this->filename);
        } elseif ($this->imageContent !== null && $this->imageContent !== '') {
            if (class_exists('CURLStringFile')) {
                $image = new \CURLStringFile($this->imageContent, $this->filename, $mimeType);
            } else {
                $path = tempnam(sys_get_temp_dir(), 'copyleaks');
                if ($path === false || file_put_contents($path, $this->imageContent) === false) {
                    throw new \RuntimeException('Could not write the image to a temporary file.');
                }
                $temporaryFiles[] = $path;
                $image = new \CURLFile($path, $mimeType, $this->filename);
            }
        } else {
            throw new InvalidArgumentException('The image is required: pass a file path, or use fromContent().');
        }

        $fields = [
            'image' => $image,
            'filename' => $this->filename,
        ];
        if ($this->sandbox !== null) {
            $fields['sandbox'] = $this->sandbox ? 'true' : 'false';
        }
        if ($this->developerPayload !== null) {
            $fields['developerPayload'] = $this->developerPayload;
        }
        return $fields;
    }

    private static function mimeType(string $filename): string
    {
        $types = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'ico' => 'image/x-icon',
        ];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return $types[$extension] ?? 'application/octet-stream';
    }
}
