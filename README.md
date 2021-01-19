# Copyleaks PHP SDK

Copyleaks SDK is a simple framework that allows you to scan text for plagiarism and detect content distribution online, using the Copyleaks plagiarism checker cloud.

Using Copyleaks SDK you can check for plagiarism in:
* Online content and webpages
* Local and cloud files (see [supported files](https://api.copyleaks.com/documentation/specifications#2-supported-file-types))
* Free text
* OCR (Optical Character Recognition) - scanning pictures with textual content (see [supported files](https://api.copyleaks.com/documentation/specifications#6-supported-image-types-ocr))

## Installation

Install using [Packagist](https://packagist.org/packages/copyleaks/php-plagiarism-checker)

```bash
composer require copyleaks/php-plagiarism-checker
```

## Register and Get Your API Key
To use the Copyleaks API you need to first be a registered user. The registration to Copyleaks takes a minute and is free of charge. [Signup](https://api.copyleaks.com/?register=true) and make sure to confirm your account.

As a signed user you can generate your personal API key. Do so on your [dashboard home](https://api.copyleaks.com/dashboard/:product) under 'API Access Credentials'.

For more information check out our [API guide](https://api.copyleaks.com/documentation/v3).

## Usage
```php
include_once('vendor/copyleaks/php-plagiarism-checker/autoload.php');
use Copyleaks\Copyleaks;

$copyleaks = new Copyleaks();
$loginResult = $copyleaks->login(<your email>,<your api key>);
echo json_encode($loginResult);
```

## Demo
See [demo.php](./demo/demo.php) under demo folder for an example.
## Read More
* [API Homepage](https://api.copyleaks.com/)
* [API Documentation](https://api.copyleaks.com/documentation)
* [Plagiarism Report](https://github.com/Copyleaks/plagiarism-report)