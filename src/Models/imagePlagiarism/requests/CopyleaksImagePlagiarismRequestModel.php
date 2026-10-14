<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

use InvalidArgumentException;

/**
 * Request model for the Image Plagiarism Detector. The image is uploaded as multipart/form-data.
 * Build it from a file with the constructor, or from bytes in memory with fromContent().
 * See https://docs.copyleaks.com/reference/actions/image-plagiarism-detector/check
 */
class CopyleaksImagePlagiarismRequestModel
{
    /**
     * Path of the image file to upload. null when the image comes from $imageContent.
     * Requirements: less than 20MB, at most 75 megapixels, JPG, JPEG, PNG, GIF, BMP, WebP or ICO.
     */
    public ?string $imagePath;

    /**
     * The image bytes to upload, used when $imagePath is null (see fromContent()).
     */
    public ?string $imageContent = null;

    /**
     * The name of the image file including its extension, at most 255 characters.
     * Allowed extensions: .jpg, .jpeg, .png, .gif, .bmp, .webp, .ico
     */
    public string $filename;

    /**
     * Use sandbox mode to test your integration without consuming credits. Returns mock results.
     * Leave null to get the server default (false).
     */
    public ?bool $sandbox;

    /**
     * A string that is returned unchanged in the response, to match the result with your own records.
     */
    public ?string $developerPayload;

    /**
     * @param string $imagePath Path of the image file to upload.
     * @param string $filename The name of the image file including its extension.
     * @param bool|null $sandbox Use sandbox mode (free, mock results). null = server default (false).
     * @param string|null $developerPayload A string returned unchanged in the response.
     */
    public function __construct(string $imagePath, string $filename, ?bool $sandbox = null, ?string $developerPayload = null)
    {
        $this->imagePath = $imagePath;
        $this->filename = $filename;
        $this->sandbox = $sandbox;
        $this->developerPayload = $developerPayload;
    }

    /**
     * Builds a request from image bytes held in memory.
     *
     * @param string $imageContent The image bytes.
     * @param string $filename The name of the image file including its extension.
     * @param bool|null $sandbox Use sandbox mode (free, mock results). null = server default (false).
     * @param string|null $developerPayload A string returned unchanged in the response.
     */
    public static function fromContent(string $imageContent, string $filename, ?bool $sandbox = null, ?string $developerPayload = null): self
    {
        $model = new self('', $filename, $sandbox, $developerPayload);
        $model->imagePath = null;
        $model->imageContent = $imageContent;
        return $model;
    }

    /**
     * Builds the multipart form fields: image, filename, sandbox and developerPayload.
     * When the image comes from memory on PHP 8.0 or older, its bytes are written to a temporary file,
     * whose path is added to $temporaryFiles so the caller can delete it after the request.
     *
     * @param string[] $temporaryFiles Receives the paths of temporary files created for the upload.
     * @return array<string, string|\CURLFile> The form fields.
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
