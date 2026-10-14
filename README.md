# Copyleaks SDK
The official [Copyleaks](https://copyleaks.com/) PHP library, supporting PHP versions >=7.4.0.

Copyleaks is the integrity layer for your tech stack that helps organizations and individuals understand what's human-made and original, AI-generated, or manipulated across text, image, audio and video.

This SDK gives you access to the following Copyleaks products:

*   AI Text Detection
*   AI Image Detection
*   AI Video Detection
*   Plagiarism Checker (Authenticity scans: file, URL, OCR)
*   Image Plagiarism Detection
*   Grammar Checker
*   Text Moderation

## 🚀 Getting Started
Before you start, ensure you have the following:

*   An active Copyleaks account. If you don’t have one, [sign up for free](https://copyleaks.com/signup).
*   You can find your API key on the [API Dashboard](https://api.copyleaks.com/dashboard).

Once you have your account and API key:

**Install the SDK**:

Install using [Packagist](https://packagist.org/packages/copyleaks/copyleaks)
```bash
composer require copyleaks/copyleaks
```
This package was previously published as `copyleaks/php-plagiarism-checker`. To switch, run `composer remove copyleaks/php-plagiarism-checker && composer require copyleaks/copyleaks`. The `Copyleaks\` namespace is unchanged.

## 📚 Documentation
To learn more about how to use Copyleaks API please check out our [Documentation](https://docs.copyleaks.com/resources/sdks/php/). 

## 💡 Usage Examples
Here are some common usage examples for the Copyleaks SDK. You can also see a comprehensive code example in the `demo.php` file on our GitHub repository: [demo.php](https://github.com/Copyleaks/PHP-Plagiarism-Checker/blob/master/demo/demo.php).

### Get Authentication Token
This example demonstrates how to log in to the Copyleaks API and obtain an authentication token.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksFileSubmissionModel;
    use Copyleaks\SubmissionProperties;
    use Copyleaks\SubmissionWebhooks;

    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    $WEBHOOK_URL = 'https://your-server.com/webhook/{STATUS}';
    // --------------------

    // Log in to the Copyleaks API
    echo "Authenticating...\n";
    $copyleaks = new Copyleaks();
    $loginToken = $copyleaks->login($EMAIL_ADDRESS, $KEY);
    echo "✅ Logged in successfully!\n";

```
For a detailed understanding of the authentication process, refer to the Copyleaks Login Endpoint [Documentation](https://docs.copyleaks.com/reference/actions/account/login).
##
### Submit Text for Plagiarism Scan
This example shows how to prepare and submit raw text content for a plagiarism scan.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksFileSubmissionModel;
    use Copyleaks\SubmissionProperties;
    use Copyleaks\SubmissionWebhooks;

    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    $WEBHOOK_URL = 'https://your-server.com/webhook/{STATUS}';
    // --------------------

    // Prepare your content for scanning
    echo "Submitting text for scanning...\n";
    $textToScan = "Hello world, this is a test.";
    $base64Content = base64_encode($textToScan);
    $scanId = time();

    // Configure the scan
    $webhooks = new SubmissionWebhooks($WEBHOOK_URL);
    $properties = new SubmissionProperties($webhooks);
    $properties->sandbox = true; // Turn on sandbox mode for testing

    $submission = new CopyleaksFileSubmissionModel($base64Content, 'test.txt', $properties);

    // Submit the scan to Copyleaks
    $copyleaks->submitFile($loginToken, $scanId, $submission);
    echo "🚀 Scan submitted successfully! Scan ID: " . $scanId . "\n";
    echo "You will be notified via your webhook when the scan is complete.\n";

```
For a full guide please refer to our step by step [Guide](https://docs.copyleaks.com/guides/authenticity/detect-plagiarism-text)

For a detailed understanding of the plagiarism detection process, refer to the Copyleaks Submit Endpoint [Documentation](https://docs.copyleaks.com/reference/actions/scans/submit-file)
##
### Reading the AI alert from the completed webhook
When a scan submitted with AI detection (`aiGeneratedText.detect`) finds AI-generated text, the completed webhook carries a `suspected-ai-text` alert. Its `additionalData` is the AI detection result encoded as a JSON string, which the SDK decodes for you.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\CompletedWebhook;

    // In your handler for the 'completed' webhook
    $webhook = CompletedWebhook::fromArray(json_decode(file_get_contents('php://input'), true));

    $aiAlert = $webhook->getAIDetectionAlert(); // null when the scan produced no AI alert
    if ($aiAlert !== null) {
        try {
            // null when the alert has no data; throws \JsonException when the data is not valid JSON
            $aiResult = $aiAlert->getAIDetectionResult();
            if ($aiResult !== null && $aiResult->summary !== null) {
                error_log("Model " . $aiResult->modelVersion . ": AI " . $aiResult->summary->ai . ", human " . $aiResult->summary->human);
                foreach ($aiResult->results as $result) {
                    error_log("Segment classification: " . $result->classification); // 1 = human, 2 = AI
                }
            }
        } catch (\JsonException $e) {
            error_log("Could not decode the AI alert: " . $e->getMessage());
        }
    }

    // Acknowledge the webhook even when the AI alert could not be decoded.
    http_response_code(200);
```
`getAIDetectionAlert()` returns null when the completed webhook contains no suspected-ai-text alert. The raw JSON string stays available in `$aiAlert->additionalData`.
##
### AI Text Detection
Use the AI detection client to determine if content was generated by artificial intelligence.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;

    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    // --------------------

    $sampleText = "Lions are social animals, living in groups called prides, typically consisting of several females, their offspring, and a few males. Female lions are the primary hunters, working together to catch prey. Lions are known for their strength, teamwork, and complex social structures.";

    $submission = new CopyleaksNaturalLanguageSubmissionModel(
      $sampleText,
    );
    $submission->sandbox = true;

    $response = $copyleaks->aiDetectionClient->submitNaturalLanguage($authToken, time(), $submission);

```

### Grammar Checker (WritingAssistantClient)
Get intelligent suggestions for improving grammar, spelling, style, and overall writing quality.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
    use Copyleaks\ScoreWeights;
    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    // --------------------

    $sampleText = "Lions are the only cat that live in groups, called pride. A prides typically consists of a few adult males, several feales, and their offspring. This social structure is essential for hunting and raising young cubs. Female lions, or lionesses are the primary hunters of the prid. They work together in cordinated groups to take down prey usually targeting large herbiores like zbras, wildebeest and buffalo. Their teamwork and strategy during hunts highlight the intelligence and coperation that are key to their survival.";

    $scoreWeights = new ScoreWeights(
      0.1, 0.2, 0.3, 0.4
    );

    $submission = new CopyleaksWritingAssistantSubmissionModel(
      $sampleText,
    );

    $submission->sandbox = true;
    $submission->score = $scoreWeights;

    $response = $copyleaks->writingAssistantClient->submitText($authToken, time(), $submission);

```
For a full guide please refer to our step by step [Guide](https://docs.copyleaks.com/guides/writing/check-grammar/)

For a detailed understanding of the Grammar Checker (WritingAssistantClient) process, refer to the Copyleaks writing feedback Endpoint [Documentation](https://docs.copyleaks.com/reference/actions/writing-assistant/check/)
##
### Text Moderation
Scan and moderate text content for unsafe, inappropriate, or policy-violating material across various categories.

```php
<?php

    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksTextModerationRequestModel;
    use Copyleaks\CopyleaksTextModerationLabel;
    use Copyleaks\CopyleaksTextModerationConstants;
    use Copyleaks\CopyleaksTextModerationLanguages;
    use Copyleaks\CopyleaksTextModerationResponseModel;
    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    // --------------------

    $labelsArray=[
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::ADULT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::TOXIC_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::VIOLENT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::PROFANITY_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::SELF_HARM_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::HARASSMENT_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::HATE_SPEECH_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::DRUGS_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::FIREARMS_V1),
        new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::CYBERSECURITY_V1)
    ]; // labels

     $textModerationRequest = new CopyleaksTextModerationRequestModel(
      "This is some text to scan.", // text
      true,                        // sandbox mode
      CopyleaksTextModerationLanguages::ENGLISH, // language, or null to detect it automatically
      $labelsArray                 // 1 to 16 labels
      );

    $response = $copyleaks->textModerationClient->submitText($authToken, time(), $textModerationRequest);
    $textModerationResponse = CopyleaksTextModerationResponseModel::fromArray(json_decode(json_encode($response), true));

```
For a full guide please refer to our step by step [Guide](https://docs.copyleaks.com/guides/moderation/moderate-text/)

For a detailed understanding of the Text moderation process, refer to the Copyleaks text moderation Endpoint [Documentation](https://docs.copyleaks.com/reference/actions/text-moderation/check/)
##
### AI Image Detection
Determine if a given image was generated or partially generated by an AI.

```php
    <?php
    require_once(__DIR__ . '/vendor/autoload.php');

    use Copyleaks\Copyleaks;
    use Copyleaks\CopyleaksAuthToken;
    use Copyleaks\CopyleaksAiImageDetectionRequestModel;
    use Copyleaks\CopyleaksAiImageDetectionResponseModel;
    use Copyleaks\CopyleaksAiImageDetectionModels;
    // --- Your Credentials ---
    $EMAIL_ADDRESS = 'YOUR_EMAIL_ADDRESS';
    $KEY = 'YOUR_API_KEY';
    // --

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
```
For a full guide please refer to our step by step [Guide](https://docs.copyleaks.com/guides/ai-detector/ai-image-detection/)

For a detailed understanding of the AI Image Detection process, refer to the Copyleaks AI image detection Endpoint [Documentation](https://docs.copyleaks.com/reference/actions/ai-image-detector/check/)

### AI Image Detection options
Ask for a heatmap mask and locate objects in the image. Both options are optional.

```php
<?php
    use Copyleaks\CopyleaksAiImageDetectionRequestModel;
    use Copyleaks\CopyleaksAiImageDetectionResponseModel;
    use Copyleaks\CopyleaksAiImageDetectionModels;
    use Copyleaks\CopyleaksAiImageMaskTypes;
    use Copyleaks\CopyleaksAiImageObjectDetectionItem;

    $submission = new CopyleaksAiImageDetectionRequestModel(
        base64_encode(file_get_contents('photo.png')),
        'photo.png',
        CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
        true,                                   // sandbox
        CopyleaksAiImageMaskTypes::HEATMAP,     // 'binary' (default), 'heatmap' or 'none'
        [new CopyleaksAiImageObjectDetectionItem('face')] // at most 3 objects
    );

    $response = $copyleaks->aiImageDetectionClient->submit($authToken, 'my-image-scan', $submission);
    $result = CopyleaksAiImageDetectionResponseModel::fromArray(json_decode(json_encode($response), true));
    // $result->isAiDetected, $result->result->labels (heatmap only), $result->detectedObjects
```

### AI Image Detection (asynchronous)
Submit the URL of an image. The call returns right away, and the result is sent to your webhook. The webhook body has the same shape as the synchronous response.

```php
<?php
    use Copyleaks\CopyleaksAiImageDetectionAsyncRequestModel;
    use Copyleaks\CopyleaksAiImageDetectionModels;
    use Copyleaks\CopyleaksAsyncWebhooks;

    $submission = new CopyleaksAiImageDetectionAsyncRequestModel(
        'https://example.com/image1.png',
        'image1.png',
        CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
        new CopyleaksAsyncWebhooks('https://your-server.com/webhook/ai-image'),
        true // sandbox
    );
    $copyleaks->aiImageDetectionClient->submitAsync($authToken, 'my-image-scan-2', $submission);

    // In your webhook handler:
    // $result = CopyleaksAiImageDetectionResponseModel::fromArray(json_decode(file_get_contents('php://input'), true));
```
Reference: [AI Image Detector Async](https://docs.copyleaks.com/reference/actions/ai-image-detector/submit)

### AI Video Detection
Submit the URL of a video. The call returns right away, and the result is sent to your webhook.

```php
<?php
    use Copyleaks\CopyleaksAiVideoDetectionRequestModel;
    use Copyleaks\CopyleaksAiVideoDetectionModels;
    use Copyleaks\CopyleaksAiVideoDetectionResponseModel;
    use Copyleaks\CopyleaksAsyncWebhooks;

    $submission = new CopyleaksAiVideoDetectionRequestModel(
        'https://example.com/my-video.mp4',
        'my-video.mp4',
        CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO,
        new CopyleaksAsyncWebhooks('https://your-server.com/webhook/ai-video'),
        true // sandbox
    );
    $copyleaks->aiVideoDetectionClient->submit($authToken, 'my-video-scan', $submission);

    // In your webhook handler:
    $result = CopyleaksAiVideoDetectionResponseModel::fromArray(json_decode(file_get_contents('php://input'), true));
    // $result->isAiDetected, $result->summary->overallAIRatio, $result->audioResult, $result->visualResult
```
Reference: [AI Video Detection](https://docs.copyleaks.com/reference/actions/ai-video-detector/submit)

### Image Plagiarism Detection
Search the web for copies of an image. The image is uploaded as a file (multipart/form-data), and the result comes back in the response.

```php
<?php
    use Copyleaks\CopyleaksImagePlagiarismRequestModel;
    use Copyleaks\CopyleaksImagePlagiarismResponseModel;

    $submission = new CopyleaksImagePlagiarismRequestModel('/path/to/my-photo.jpg', 'my-photo.jpg', true); // sandbox
    // Or from bytes in memory: CopyleaksImagePlagiarismRequestModel::fromContent($bytes, 'my-photo.jpg', true);

    $response = $copyleaks->imagePlagiarismClient->submit($authToken, 'my-image-check', $submission);
    $result = CopyleaksImagePlagiarismResponseModel::fromArray(json_decode(json_encode($response), true));
    foreach ($result->matches->internet as $match) {
        echo $match->url, ' matchType=', $match->matchType, "\n"; // 0 = full, 1 = partial
    }
```
Reference: [Image Plagiarism Detection](https://docs.copyleaks.com/reference/actions/image-plagiarism-detector/check)

### More scan options
`SubmissionProperties` and its inner objects cover every documented scan option. New options are optional constructor arguments at the end, and every model property is public, so you can also set them after construction.

```php
<?php
    use Copyleaks\AIGeneratedText;
    use Copyleaks\AIGeneratedTextExplain;
    use Copyleaks\CrossLanguages;
    use Copyleaks\InternalAISourceMatch;
    use Copyleaks\Language;
    use Copyleaks\ScoreWeights;
    use Copyleaks\SubmissionCourse;
    use Copyleaks\SubmissionOverview;
    use Copyleaks\SubmissionProperties;
    use Copyleaks\SubmissionReferences;
    use Copyleaks\SubmissionScanning;
    use Copyleaks\SubmissionWebhooks;
    use Copyleaks\SubmissionWritingFeedback;

    $properties = new SubmissionProperties(new SubmissionWebhooks('https://your-server.com/webhook/{STATUS}'));
    $properties->sandbox = true;
    $properties->aiGeneratedText = new AIGeneratedText(true, 2, new AIGeneratedTextExplain(true)); // detect, sensitivity, AI Logic
    $properties->writingFeedback = new SubmissionWritingFeedback(true, new ScoreWeights(1.0, 0.5));  // Grammar Checker in the scan
    $properties->overview = new SubmissionOverview(true);                                          // Gen-AI Overview
    $properties->references = new SubmissionReferences(true);                                      // Citation Checker
    $properties->internalAiSourceMatch = new InternalAISourceMatch(true);
    $properties->scanTimeZone = 'America/New_York';
    $properties->course = new SubmissionCourse('course-101');
    $properties->scanning = new SubmissionScanning();
    $properties->scanning->crossLanguages = new CrossLanguages([new Language('fr')]); // see getAllowedCrossLanguages()
```
Options you leave null are not sent, so the server applies its documented default.

The export request can also send the AI detection, Grammar Checker and overview reports, and no longer needs `results` or `crawledVersion`:

```php
<?php
    use Copyleaks\CopyleaksExportModel;
    use Copyleaks\ExportAiDetection;

    $export = new CopyleaksExportModel('https://your-server.com/export/completed');
    $export->aiDetection = new ExportAiDetection('https://your-server.com/export/ai', 'POST');
    $copyleaks->export($authToken, 'my-scan-id', 'my-export-id', $export);
```

### AI Text Detection options and credits

```php
<?php
    use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;

    // text, sandbox, language (null = detect), explain (AI Logic), sensitivity (1 to 3)
    $submission = new CopyleaksNaturalLanguageSubmissionModel($sampleText, true, null, true, 2);
    $response = $copyleaks->aiDetectionClient->submitNaturalLanguage($authToken, 'my-ai-scan', $submission);

    $balance = $copyleaks->aiDetectionClient->getCreditsBalance($authToken); // {"credits": 382}
```

### Lookups

```php
<?php
    $languages = $copyleaks->getAllowedCrossLanguages();              // documentLanguages, resultLanguages (no login needed)
    $aiFileTypes = $copyleaks->getSupportedAiTextDetectionFileTypes(); // supportedAiFileTypes (no login needed)
    $repository = $copyleaks->getRepositoryInfo($authToken, 'my-repository-id'); // Private Cloud Hub, Admin role
```

### Timeouts, connections and errors
Every request has a connect timeout (10 seconds) and a total timeout (120 seconds). Change them for the whole process:

```php
<?php
    use Copyleaks\CopyleaksConfig;

    CopyleaksConfig::SET_CONNECT_TIMEOUT(5);
    CopyleaksConfig::SET_TIMEOUT(300); // 0 means no limit
```
The SDK keeps the connection to Copyleaks open between calls in the same PHP process, so consecutive calls skip the connection setup. Call `Copyleaks\HttpClientService::closeConnections()` to close it.

Errors are thrown as exceptions that carry the HTTP status code:

```php
<?php
    use Copyleaks\CommandException;
    use Copyleaks\RateLimitException;

    try {
        $copyleaks->submitFile($authToken, 'my-scan', $submission);
    } catch (RateLimitException $e) {
        sleep($e->getRetryAfterSeconds() ?? 60);             // 429
    } catch (CommandException $e) {
        error_log($e->getStatusCode() . ' ' . $e->getResponseBody()); // getStatusCode() is 0 when no response arrived, for example after a timeout
    }
```

##
### Further Resources

*   **Copyleaks API Dashboard:** Manage your API keys, monitor usage, and view analytics from your personalized dashboard. [Access Dashboard](https://api.copyleaks.com/dashboard)
*   **Copyleaks SDK Documentation:** Explore comprehensive guides, API references, and code examples for seamless integration. [Read Documentation](https://docs.copyleaks.com/resources/sdks/overview/)


## Support
* If you need assistance, please contact Copyleaks Support via our support portal: Contact Copyleaks [Support](https://help.copyleaks.com/s/contactsupport).
* To arrange a product demonstration, book a demo here: [Booking Link](https://copyleaks.com/book-a-demo).
