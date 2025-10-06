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
    
    $base64Image = base64_encode(file_get_contents($imagePath));
    $fileName = basename($imagePath);
    
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