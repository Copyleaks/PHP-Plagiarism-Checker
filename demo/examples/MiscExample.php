<?php

namespace Demo\Examples;

use Copyleaks\Copyleaks;

function runMiscExample(Copyleaks $copyleaks): void
{
    $ocrSupportedLanguages = $copyleaks->getOCRSupportedLanguages();
    logInfo("-OCR Supported Languages-", $ocrSupportedLanguages);

    $supportedFileTypes = $copyleaks->getSupportedFileTypes();
    logInfo("-Supported File Types-", $supportedFileTypes);

    $releaseNotes = $copyleaks->getReleaseNotes();
    logInfo("-Release Notes-", $releaseNotes);
}