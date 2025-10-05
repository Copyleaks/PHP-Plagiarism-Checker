<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksAiImageDetectionRequestModel;
use Copyleaks\CopyleaksAiImageDetectionResponseModel;
use Copyleaks\CopyleaksAiImageDetectionModels;

/**
 * Example demonstrating AI image detection functionality
 */
function runImageDetectionExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    // Read and encode your image file to base64
    $imagePath = "path/to/your/image.jpg"; // Update this path to your actual image file
    
    // Check if the image file exists and read it
    if (file_exists($imagePath)) {
        $base64Image = base64_encode(file_get_contents($imagePath));
        $fileName = basename($imagePath);
    } else {
        // Fallback to sample base64 image (1x1 pixel PNG) if file doesn't exist
        echo "Image file not found at: $imagePath. Using sample image.\n";
        $base64Image = "iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8/5+hHgAHggJ/PchI7wAAAABJRU5ErkJggg==";
        $fileName = "sample-image.png";
    }

    $submission = new CopyleaksAiImageDetectionRequestModel(
        $base64Image,
        $fileName,
        CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
        true // Use sandbox mode for testing
    );

    $response = $copyleaks->aiImageDetectionClient->submit($authToken, time(), $submission);
    $imageDetectionResponse = CopyleaksAiImageDetectionResponseModel::fromArray(json_decode(json_encode($response), true));

    logInfo('AI Image Detection Example', $imageDetectionResponse);
}