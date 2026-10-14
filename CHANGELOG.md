# Changelog

All notable changes to the Copyleaks PHP SDK are listed here.

## 5.3.0

The package is now published on Packagist as `copyleaks/copyleaks`. Versions up to v5.2.0 were published as `copyleaks/php-plagiarism-checker`. The PHP code you write does not change: the `Copyleaks\` namespace and all class names are the same as before. Read the upgrade notes below.

### Added

- **New submit properties.**
  - `Copyleaks\AISourceMatch`, passed as the new `$aiSourceMatch` argument of `Copyleaks\SubmissionProperties`: `new AISourceMatch(true)` finds online sources suspected of containing AI-generated text. It currently applies only to documents detected as English. Leave it null to get the server default (disabled).
  - `$displayLanguage` argument and property of `Copyleaks\SubmissionProperties`: the language of the scan's PDF report. One of `'en'`, `'es'`, `'pt'`, `'fr'`, `'de'`, `'it'` (lowercase, case-sensitive). Leave it null to get the server default (`'en'`).
  - `$reportVersion` argument and property of `Copyleaks\SubmissionPDF`: which version of the PDF report to generate. One of `'v1'`, `'v2'`, `'v3'`, `'latest'` (lowercase, case-sensitive). It overrides the older `$version` property. When it is null, the server uses `$version` if set, and otherwise its default.
  - The new arguments come after the existing ones and default to null, so existing calls keep working.
- **AI alert in the completed webhook.**
  - `CompletedWebhook::getAIDetectionAlert()` returns the `suspected-ai-text` alert as a `Copyleaks\Alerts`, or null when the scan has no AI alert.
  - `Alerts::getAIDetectionResult()` and the shortcut `CompletedWebhook::getAIDetectionResult()` decode the alert's `additionalData` JSON string into a `Copyleaks\CopyleaksAiTextDetectionResponseModel`, with `summary->ai`, `summary->human`, `modelVersion`, `results`, `translation` and `explain`. They return null when there is no AI alert, or when the data is empty or is JSON that is not an object. They throw `\JsonException` when `additionalData` is not valid JSON. The raw string stays available in `$alert->additionalData`.
  - New constant `Copyleaks\CopyleaksAlertCodes::SUSPECTED_AI_TEXT` (`"suspected-ai-text"`).
  - New AI Text Detection model classes, loaded by the package autoloader.
- **New endpoints.**
  - Image Plagiarism Detection: `$copyleaks->imagePlagiarismClient->submit($token, $scanId, new CopyleaksImagePlagiarismRequestModel($imagePath, $filename, $sandbox, $developerPayload))` uploads the image as multipart/form-data and returns the matches. `CopyleaksImagePlagiarismRequestModel::fromContent()` takes the image bytes instead of a file path. Read the response with `CopyleaksImagePlagiarismResponseModel::fromArray()`.
  - AI Video Detection: `$copyleaks->aiVideoDetectionClient->submit($token, $scanId, new CopyleaksAiVideoDetectionRequestModel(...))` submits a video URL. The result is sent to your webhook; read it with `CopyleaksAiVideoDetectionResponseModel::fromArray()`. Model constant: `CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO`.
  - AI Image Detection, asynchronous: `$copyleaks->aiImageDetectionClient->submitAsync($token, $scanId, new CopyleaksAiImageDetectionAsyncRequestModel(...))` submits an image URL. The result is sent to your webhook in the same shape as the synchronous response.
  - `$copyleaks->aiDetectionClient->getCreditsBalance($token)`: the AI Text Detection credit balance.
  - `$copyleaks->getAllowedCrossLanguages()`: the languages allowed in cross-language plagiarism detection. No login needed.
  - `$copyleaks->getSupportedAiTextDetectionFileTypes()`: the file types AI Text Detection accepts. No login needed.
  - `$copyleaks->getRepositoryInfo($token, $repositoryId)`: information about a Private Cloud Hub repository.
  - `Copyleaks\CopyleaksAsyncWebhooks` holds the webhook URL and headers of the two asynchronous detections.
- **New scan options** (`Copyleaks\SubmissionProperties` and its inner objects). All are optional and come after the existing constructor arguments. Options left null are not sent.
  - `$writingFeedback` (`SubmissionWritingFeedback`): run the Grammar Checker inside the scan, with optional `ScoreWeights`.
  - `$overview` (`SubmissionOverview`): the Gen-AI Overview and its ignore options.
  - `$references` (`SubmissionReferences`): the Citation Checker.
  - `$internalAiSourceMatch` (`InternalAISourceMatch`): Internal AI Source Match.
  - `$scanTimeZone`: the IANA time zone of the PDF report, for example `'America/New_York'`.
  - `$course`, `$assignment` and `$institution` (`SubmissionCourse`, `SubmissionAssignment`, `SubmissionInstitution`).
  - `AIGeneratedText`: `$sensitivity` (1 to 3) and `$explain` (`AIGeneratedTextExplain`, AI Logic).
  - `SubmissionScanning`: `$include` (`SubmissionScanningInclude`) and `$crossLanguages` (`CrossLanguages` of `Language` codes).
  - `SubmissionScanningExclude`: `$backlinksDomains` and `$text`. `$idPattern` is now optional.
  - `SubmissionIndexing`: `$copyleaksDb`. `$repositories` is now optional.
  - `SubmissionWebhooks`: `$newResultHeaders` and `$statusHeaders`.
  - `ReportCustomizationColors`: `$aiDetection` and `$writingFeedback`.
  - `PdfReportVersion::V3`.
- **New request options.**
  - Submit URL: `$verb` and `$headers` on `CopyleaksURLSubmissionModel`, to fetch pages that need POST, PUT or custom headers.
  - Delete: `$headers` for the completion webhook on `CopyleaksDeleteRequestModel`.
  - Export: `$completionWebhookHeaders`, and the `$aiDetection`, `$writingFeedback` and `$overview` reports (`ExportAiDetection`, `ExportWritingFeedback`, `ExportOverview`) on `CopyleaksExportModel`.
  - AI Text Detection: `$explain` (AI Logic) and `$sensitivity` on `CopyleaksNaturalLanguageSubmissionModel`.
  - AI Image Detection: `$maskType` (`CopyleaksAiImageMaskTypes`: binary, heatmap or none) and `$objectDetection` (up to 3 `CopyleaksAiImageObjectDetectionItem`) on `CopyleaksAiImageDetectionRequestModel`.
- **New response and webhook fields.**
  - AI Image Detection response: `isAiDetected`, `detectedObjects`, `result->labels` (heatmap) and `imageInfo->metadata->contentSummary`.
  - AI Text Detection response: `scannedDocument`.
  - Text Moderation response: `modelVersion`.
  - Completed webhook: `referencesValidation` (Citation Checker summary) and `results->internalAIData`. The new-result webhook also has `internalAIData`.
  - Error webhook: `error->type`, `error->id`, `error->url` and `error->details`.
- **Connection settings.** `CopyleaksConfig::SET_CONNECT_TIMEOUT()` and `CopyleaksConfig::SET_TIMEOUT()` set the connect and total time limits of every request. `HttpClientService::closeConnections()` closes the open connection.
- **Error details.** `CommandException`, `RateLimitException` and `UnderMaintenanceException` now carry the HTTP status as their code, and have `getStatusCode()`, `getResponseBody()`, `getResponseHeaders()`, `getResponseHeader()` and `getRetryAfterSeconds()`.

### Changed

- **Timeouts.** Requests now time out: 10 seconds to connect and 120 seconds in total. Before, a stalled connection could wait forever. Both limits can be changed (see Upgrade notes).
- **Connection reuse.** The SDK keeps one connection open per PHP process and reuses it, so consecutive calls skip the connection and TLS setup.
- **Compressed responses.** The SDK asks for compressed responses and decompresses them.
- **Server defaults are kept.** `SubmissionFilter`'s `$minorChangesEnabled` and `$relatedMeaningEnabled`, and `SubmissionScanning`'s `$internet`, now default to null, so they are not sent and the server applies its default (true). Before, building one of these objects sent `false` and silently turned off these matches.
- **Text Moderation labels.** A request takes 1 to 16 labels, the server's limit. Before, up to 32 were accepted and then rejected by the server.
- **User-Agent.** Requests send `php-sdk/5.3.0`.
- **Package contents.** The Composer package no longer includes the `demo` folder.
- **Package renamed to `copyleaks/copyleaks`.** `composer.json` declares `"replace": {"copyleaks/php-plagiarism-checker": "self.version"}`. Composer therefore never installs both packages side by side, and once your project requires `copyleaks/copyleaks`, a dependency on `copyleaks/php-plagiarism-checker` `^5` is met by it. The package description now describes the Copyleaks platform.

### Fixed

- Text that contains JSON or code with `"name":null` is now sent unchanged. Before, those parts were cut out of the request, which broke the JSON and failed with a 400 error. This affected AI Text Detection, the Grammar Checker, Text Moderation and any `developerPayload`.
- `SubmissionFilter`'s `$allowSameDomain` is now sent. Before, it was private and never reached the server.
- `CopyleaksTextModerationRequestModel` accepts a null `$language` to detect the language automatically. Before, null threw a `TypeError`.
- `CopyleaksExportModel` no longer requires `$results` and `$crawledVersion`, so you can export only the PDF report or the new reports.
- `SubmissionPDF` accepts a null `$title` and `$largeLogo`, which are then not sent.
- Login sends a JSON body with the `Content-Type`, `Accept` and `User-Agent` headers. The public lookups (OCR languages, supported file types, release notes, correction types) now send the `User-Agent` header too.
- Requests no longer send `Expect: 100-continue`, which could add up to a second to uploads and to resending webhooks.
- Scan IDs and other path values are URL-encoded, so IDs with characters such as spaces, `/` or `%` reach the right endpoint.
- A request body that cannot be encoded as JSON (for example text that is not valid UTF-8) now throws `\JsonException` before anything is sent. Before, an empty body was sent.
- Delete no longer sends an empty `completionWebhook` when you did not set one.
- Submitting no longer changes your model objects. Before, null properties were removed from them, and reading those properties afterwards threw an error.
- `CrossLanguages`, `Language`, `ExcludeCode`, `CustomMetadata`, `ReportCustomizationColors`, `PdfReportVersion`, `ScanMethodAlgorithm` and `MaskingPolicy` are now loaded by the package autoloader.
- The SDK loads its own files no matter which directory your script runs from.
- Loading the SDK on PHP 8.4 and later no longer emits deprecation notices, and requests no longer call the deprecated `curl_close()`.
- Webhook models built with `fromArray()` now fill `developerPayload`, and `status` for status webhooks.
- `CopyleaksTextModerationResponseModel::fromArray()` accepts a response without `legend` or with fewer `scannedDocument` fields.
- `getSupportedFileTypes()` calls the documented `/v3/miscellaneous/supported-plagiarism-file-types` path. The response is the same.

### Upgrade notes

- **Switch packages:** run `composer remove copyleaks/php-plagiarism-checker`, then `composer require copyleaks/copyleaks`. In `composer.json`, this replaces `"copyleaks/php-plagiarism-checker": "^5.2"` with `"copyleaks/copyleaks": "^5.3"`.
- **Your code:** the `Copyleaks\` namespace, the class names and `require 'vendor/autoload.php'` stay the same. No code changes are needed for the rename.
- **File paths:** the package now installs to `vendor/copyleaks/copyleaks/` instead of `vendor/copyleaks/php-plagiarism-checker/`. Update any code or build script that refers to the old folder directly.
- **Webhooks:** the new methods are additions. Existing code that reads `notifications` or `Alerts` keeps working.
- **Timeouts:** a request that takes longer than 120 seconds now fails with a `CommandException` whose `getStatusCode()` is 0. If you upload very large files over a slow connection, raise the limit with `CopyleaksConfig::SET_TIMEOUT($seconds)`, or pass 0 for no limit.
- **Filter and scanning defaults:** if you relied on `new SubmissionFilter()` turning off minor-changes and related-meaning matches, or on `new SubmissionScanning()` turning off internet scanning, pass `false` explicitly.
- **Error codes:** `CommandException::getCode()` is now the HTTP status instead of 0. Messages keep their format.
- **Text Moderation:** pass at most 16 labels. `$sandbox` and `$language` now have defaults (`false` and null), so you can leave them out with named arguments. `$labels` is still required.
- All new constructor arguments come after the existing ones and are optional, so existing calls keep working.
