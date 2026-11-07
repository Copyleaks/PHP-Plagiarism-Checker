<?php

namespace Copyleaks;

use InvalidArgumentException;

/**
 * AI Image Detection request that uploads the image as multipart/form-data, without the base64 overhead. Send it
 * with AiImagedetectionClient::submitFile(). Build it from a file with the constructor, or from bytes in memory with
 * fromContent().
 * See https://docs.copyleaks.com/reference/actions/ai-image-detector/check
 */
class CopyleaksAiImageDetectionFileRequestModel
{
    /**
     * Local image file to upload; null when the image comes from $imageContent. A plain file path: stream wrappers
     * (scheme://) are rejected. Do not build it from untrusted input; pass uploaded bytes with fromContent() instead.
     * At least 400 px on each side and at most 27 megapixels (e.g. 6000x4500), under 32 MB; PNG, JPEG, BMP, WebP,
     * HEIC/HEIF or AVIF.
     */
    public ?string $imagePath;

    /** Image bytes, used when $imagePath is null (see fromContent()). */
    public ?string $imageContent = null;

    /**
     * File name with extension (.png, .jpg, .jpeg, .bmp, .webp, .heic, .heif, .avif), 5 to 254 chars. Sent as the
     * upload's file name; it is never used to read a local file.
     */
    public string $filename;

    /** Full model name or alias, see CopyleaksAiImageDetectionModels. */
    public string $model;

    /** Return mock results without using credits. For development only. */
    public bool $sandbox;

    /** 'binary', 'heatmap' or 'none', see CopyleaksAiImageMaskTypes. null = server default ('binary'). */
    public ?string $maskType;

    /**
     * Up to 3 objects to locate, e.g. new CopyleaksAiImageObjectDetectionItem('face'). null = no object detection.
     * @var CopyleaksAiImageObjectDetectionItem[]|null
     */
    public ?array $objectDetection;

    /**
     * @param string $imagePath
     * @param string $filename
     * @param string $model
     * @param bool $sandbox
     * @param string|null $maskType
     * @param CopyleaksAiImageObjectDetectionItem[]|null $objectDetection
     */
    public function __construct(string $imagePath, string $filename, string $model, bool $sandbox = false, ?string $maskType = null, ?array $objectDetection = null)
    {
        $this->imagePath = $imagePath;
        $this->filename = $filename;
        $this->model = $model;
        $this->sandbox = $sandbox;
        $this->maskType = $maskType;
        $this->objectDetection = $objectDetection;
    }

    /**
     * Build a request from image bytes in memory.
     * @param string $imageContent
     * @param string $filename
     * @param string $model
     * @param bool $sandbox
     * @param string|null $maskType
     * @param CopyleaksAiImageObjectDetectionItem[]|null $objectDetection
     */
    public static function fromContent(string $imageContent, string $filename, string $model, bool $sandbox = false, ?string $maskType = null, ?array $objectDetection = null): self
    {
        $request = new self('', $filename, $model, $sandbox, $maskType, $objectDetection);
        $request->imagePath = null;
        $request->imageContent = $imageContent;
        return $request;
    }

    /** @return string[] Validation errors; empty if valid. Checks the same fields as CopyleaksAiImageDetectionRequestModel::validate(). */
    public function validate(): array
    {
        $errors = [];

        if (($this->imagePath === null || $this->imagePath === '') && ($this->imageContent === null || $this->imageContent === '')) {
            $errors[] = 'Image field is required';
        }

        if ($this->filename === '') {
            $errors[] = 'FileName field is required';
        }

        if ($this->model === '') {
            $errors[] = 'Model field is required';
        }

        if ($this->filename !== '') {
            $allowedExtensions = ['.png', '.bmp', '.jpg', '.jpeg', '.webp', '.heic', '.heif', '.avif'];
            $extension = strtolower(pathinfo($this->filename, PATHINFO_EXTENSION));
            if (!in_array('.' . $extension, $allowedExtensions, true)) {
                $errors[] = 'Unsupported file extension. Allowed: ' . implode(', ', $allowedExtensions);
            }
        }

        return $errors;
    }

    /**
     * On PHP 8.0 and older, in-memory image bytes are written to a temporary file; the caller deletes it after the
     * request.
     * @param string[] $temporaryFiles Receives the paths of the temporary files.
     * @return array<string, string|\CURLFile>
     * @throws InvalidArgumentException When the image or the filename is missing or invalid.
     * @throws \RuntimeException When the temporary file cannot be created or written (PHP 8.0 and older).
     * @throws \JsonException When $objectDetection cannot be encoded as JSON.
     */
    public function toMultipartFields(array &$temporaryFiles = []): array
    {
        if ($this->filename === '') {
            throw new InvalidArgumentException('The image filename is required.');
        }
        if (preg_match('/[\x00-\x1F\x7F]/', $this->filename) === 1) {
            throw new InvalidArgumentException('The image filename must not contain control characters.');
        }
        $mimeType = self::mimeType($this->filename);

        if ($this->imagePath !== null && $this->imagePath !== '') {
            if (strpos($this->imagePath, "\0") !== false || preg_match('#^[a-z][a-z0-9+.-]+://#i', $this->imagePath) === 1
                || !is_file($this->imagePath) || !is_readable($this->imagePath)) {
                // The path is left out of the message: it may hold a signed URL or user names.
                throw new InvalidArgumentException('The image file cannot be read: imagePath must be a readable local file (scheme:// paths are not accepted).');
            }
            $image = new \CURLFile($this->imagePath, $mimeType, $this->filename);
        } elseif ($this->imageContent !== null && $this->imageContent !== '') {
            if (class_exists('CURLStringFile')) {
                $image = new \CURLStringFile($this->imageContent, $this->filename, $mimeType);
            } else {
                $path = tempnam(sys_get_temp_dir(), 'copyleaks');
                if ($path === false) {
                    throw new \RuntimeException('Could not create a temporary file for the image.');
                }
                // Listed before writing, so a failed write is deleted too.
                $temporaryFiles[] = $path;
                if (file_put_contents($path, $this->imageContent) === false) {
                    throw new \RuntimeException('Could not write the image to a temporary file.');
                }
                $image = new \CURLFile($path, $mimeType, $this->filename);
            }
        } else {
            throw new InvalidArgumentException('The image is required: pass a file path, or use fromContent().');
        }

        $fields = [
            'image' => $image,
            'filename' => $this->filename,
            'model' => $this->model,
            'sandbox' => $this->sandbox ? 'true' : 'false',
        ];
        if ($this->maskType !== null) {
            $fields['maskType'] = $this->maskType;
        }
        if ($this->objectDetection !== null) {
            $fields['objectDetection'] = RequestPayload::encode($this->objectDetection);
        }
        return $fields;
    }

    private static function mimeType(string $filename): string
    {
        $types = [
            'png' => 'image/png',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'bmp' => 'image/bmp',
            'webp' => 'image/webp',
            'heic' => 'image/heic',
            'heif' => 'image/heif',
            'avif' => 'image/avif',
        ];
        $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        return $types[$extension] ?? 'application/octet-stream';
    }
}
