# Changelog

All notable changes to the Copyleaks PHP SDK are listed here.

## 5.3.0

The package is now published on Packagist as `copyleaks/copyleaks`. Versions up to v5.2.0 were published as `copyleaks/php-plagiarism-checker`. The PHP code you write does not change: the `Copyleaks\` namespace and all class names are the same as before. Read the upgrade notes below.

### Added

- **New submit properties (BAC-5378).**
  - `Copyleaks\AISourceMatch`, passed as the new `$aiSourceMatch` argument of `Copyleaks\SubmissionProperties`: `new AISourceMatch(true)` finds online sources suspected of containing AI-generated text. It currently applies only to documents detected as English. Leave it null to get the server default (disabled).
  - `$displayLanguage` argument and property of `Copyleaks\SubmissionProperties`: the language of the scan's PDF report. One of `'en'`, `'es'`, `'pt'`, `'fr'`, `'de'`, `'it'` (lowercase, case-sensitive). Leave it null to get the server default (`'en'`).
  - `$reportVersion` argument and property of `Copyleaks\SubmissionPDF`: which version of the PDF report to generate. One of `'v1'`, `'v2'`, `'v3'`, `'latest'` (lowercase, case-sensitive). It overrides the older `$version` property. When it is null, the server uses `$version` if set, and otherwise its default.
  - The new arguments come after the existing ones and default to null, so existing calls keep working.
- **AI alert in the completed webhook (BAC-5392).**
  - `CompletedWebhook::getAIDetectionAlert()` returns the `suspected-ai-text` alert as a `Copyleaks\Alerts`, or null when the scan has no AI alert.
  - `Alerts::getAIDetectionResult()` and the shortcut `CompletedWebhook::getAIDetectionResult()` decode the alert's `additionalData` JSON string into a `Copyleaks\CopyleaksAiTextDetectionResponseModel`, with `summary->ai`, `summary->human`, `modelVersion`, `results`, `translation` and `explain`. They return null when there is no AI alert, or when the data is empty or is JSON that is not an object. They throw `\JsonException` when `additionalData` is not valid JSON. The raw string stays available in `$alert->additionalData`.
  - New constant `Copyleaks\CopyleaksAlertCodes::SUSPECTED_AI_TEXT` (`"suspected-ai-text"`).
  - New AI Text Detection model classes, loaded by the package autoloader.

### Changed

- **Package renamed to `copyleaks/copyleaks` (BAC-5374).** `composer.json` declares `"replace": {"copyleaks/php-plagiarism-checker": "self.version"}`. Composer therefore never installs both packages side by side, and once your project requires `copyleaks/copyleaks`, a dependency on `copyleaks/php-plagiarism-checker` `^5` is met by it. The package description now describes the Copyleaks platform.
- **README (BAC-5374).** The README now describes the SDK as the Copyleaks platform SDK and lists the products it covers: AI Text Detection, AI Image Detection, Plagiarism Checker, Grammar Checker and Text Moderation. Headings such as "AI Text Detection" now use the product names.

### Upgrade notes

- **Switch packages:** run `composer remove copyleaks/php-plagiarism-checker`, then `composer require copyleaks/copyleaks`. In `composer.json`, this replaces `"copyleaks/php-plagiarism-checker": "^5.2"` with `"copyleaks/copyleaks": "^5.3"`.
- **Your code:** the `Copyleaks\` namespace, the class names and `require 'vendor/autoload.php'` stay the same. No code changes are needed for the rename.
- **File paths:** the package now installs to `vendor/copyleaks/copyleaks/` instead of `vendor/copyleaks/php-plagiarism-checker/`. Update any code or build script that refers to the old folder directly.
- **Webhooks:** the new methods are additions. Existing code that reads `notifications` or `Alerts` keeps working.
