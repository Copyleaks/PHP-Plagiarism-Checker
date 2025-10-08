<?php
/********************************************************************************
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
********************************************************************************/
namespace Copyleaks;

/**
 * Request model for Copyleaks AI image detection.
 * The request body is a JSON object containing the image to analyze.
 */
class CopyleaksAiImageDetectionRequestModel
{
    /**
     * The base64-encoded image data to be analyzed for AI generation.
     * 
     * Requirements:
     * - Minimum 512×512px, maximum 16 megapixels, less than 32MB
     * - Supported formats: PNG, JPEG, BMP, WebP, HEIC/HEIF
     * 
     * @var string
     * @example "aGVsbG8gd29ybGQ="
     */
    public $base64;

    /**
     * The name of the image file including its extension.
     * 
     * Requirements:
     * - Supported extensions: .png, .bmp, .jpg, .jpeg, .webp, .heic, .heif
     * - Maximum 255 characters
     * 
     * @var string
     * @example "my-image.png"
     */
    public $fileName;

    /**
     * The AI detection model to use for analysis.
     * You can use either the full model name or its alias.
     * 
     * Available models:
     * - AI Image 1 Ultra: "ai-image-1-ultra-01-09-2025" (full name) or "ai-image-1-ultra" (alias)
     *   AI image detection model. Produces an overlay of the detected AI segments.
     * 
     * @var string
     * @example "ai-image-1-ultra-01-09-2025" or "ai-image-1-ultra"
     */
    public $model;

    /**
     * Use sandbox mode to test your integration with the Copyleaks API without consuming any credits.
     * 
     * Submit images for AI detection and get returned mock results, simulating Copyleaks' API functionality 
     * to ensure you have successfully integrated the API.
     * This feature is intended to be used for development purposes only.
     * Default value is false.
     * 
     * @var bool
     * @example false
     */
    public $sandbox;

    /**
     * Constructor for CopyleaksAiImageDetectionRequestModel
     * 
     * @param string $base64 The base64-encoded image data
     * @param string $fileName The name of the image file including its extension
     * @param string $model The AI detection model to use for analysis
     * @param bool $sandbox Use sandbox mode (default: false)
     */
    public function __construct($base64, $fileName, $model, $sandbox = false)
    {
        $this->base64 = $base64;
        $this->fileName = $fileName;
        $this->model = $model;
        $this->sandbox = $sandbox;
    }

    /**
     * Convert the model to an array for JSON serialization
     * 
     * @return array
     */
    public function toArray()
    {
        return [
            'base64' => $this->base64,
            'fileName' => $this->fileName,
            'model' => $this->model,
            'sandbox' => $this->sandbox
        ];
    }

    /**
     * Convert the model to JSON string
     * 
     * @return string
     */
    public function toJson()
    {
        return json_encode($this->toArray());
    }

    /**
     * Validate required fields
     * 
     * @return array Array of validation errors (empty if valid)
     */
    public function validate()
    {
        $errors = [];

        if (empty($this->base64)) {
            $errors[] = 'Base64 field is required';
        }

        if (empty($this->fileName)) {
            $errors[] = 'FileName field is required';
        }

        if (empty($this->model)) {
            $errors[] = 'Model field is required';
        }

        // Validate file extension if fileName is provided
        if (!empty($this->fileName)) {
            $allowedExtensions = ['.png', '.bmp', '.jpg', '.jpeg', '.webp', '.heic', '.heif'];
            $extension = strtolower(pathinfo($this->fileName, PATHINFO_EXTENSION));
            if (!in_array('.' . $extension, $allowedExtensions)) {
                $errors[] = 'Unsupported file extension. Allowed: ' . implode(', ', $allowedExtensions);
            }
        }

        return $errors;
    }
}
?>