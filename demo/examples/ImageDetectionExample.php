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
    
    $base64Image = base64_encode(file_get_contents(/*filename*/$imagePath));
    $fileName = basename(/*path*/$imagePath);
    
    $submission = new CopyleaksAiImageDetectionRequestModel(
        /*base64*/$base64Image,
        /*filename*/$fileName,
        /*model*/CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
        /*sandbox*/true // Use sandbox mode for testing
    );

    $response = $copyleaks->aiImageDetectionClient->submit(/*authToken*/$authToken, /*scanId*/time(), /*submission*/$submission);
    $imageDetectionResponse = CopyleaksAiImageDetectionResponseModel::fromArray(/*data*/json_decode(/*json*/json_encode(/*value*/$response), /*associative*/true));

    logInfo(/*message*/'AI Image Detection Example', /*context*/$imageDetectionResponse);
}