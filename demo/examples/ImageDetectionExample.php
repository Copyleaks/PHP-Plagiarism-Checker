<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksAiImageDetectionRequestModel;
use Copyleaks\CopyleaksAiImageDetectionResponseModel;
use Copyleaks\CopyleaksAiImageDetectionModels;

function runImageDetectionExample(Copyleaks $copyleaks, CopyleaksAuthToken $authToken): void
{
    $imagePath = "path/to/your/image.jpg"; // your image file
    
    $base64Image = base64_encode(file_get_contents(/*filename*/$imagePath));
    $fileName = basename(/*path*/$imagePath);
    
    $submission = new CopyleaksAiImageDetectionRequestModel(
        /*base64*/$base64Image,
        /*filename*/$fileName,
        /*model*/CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
        /*sandbox*/true
    );

    $response = $copyleaks->aiImageDetectionClient->submit(/*authToken*/$authToken, /*scanId*/time(), /*submission*/$submission);
    $imageDetectionResponse = CopyleaksAiImageDetectionResponseModel::fromArray(/*data*/json_decode(/*json*/json_encode(/*value*/$response), /*associative*/true));

    logInfo(/*message*/'AI Image Detection Example', /*context*/$imageDetectionResponse);
}