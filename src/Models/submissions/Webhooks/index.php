<?php
/*
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
*/

namespace Copyleaks;
include_once(__DIR__ . '/baseModels/Metadata.php');
include_once(__DIR__ . '/baseModels/Webhook.php');
include_once(__DIR__ . '/baseModels/StatusWebhook.php');


include_once(__DIR__ . '/ResultsModels/SharedResultsModel.php');
include_once(__DIR__ . '/NewResultsModel/NewResultScore.php');
include_once(__DIR__ . '/NewResultsModel/NewResultsInternet.php');
include_once(__DIR__ . '/NewResultsModel/NewResultsRepositories.php');


include_once(__DIR__ . '/ResultsModels/Batch.php');
include_once(__DIR__ . '/ResultsModels/Internet.php');
include_once(__DIR__ . '/ResultsModels/Database.php');
include_once(__DIR__ . '/ResultsModels/Repositories.php');
include_once(__DIR__ . '/ResultsModels/RepositoryMetadata.php');
include_once(__DIR__ . '/ResultsModels/Score.php');
include_once(__DIR__ . '/ResultsModels/Tags.php');
include_once(__DIR__ . '/ResultsModels/InternalAIData.php');

include_once(__DIR__ . '/CompletedModels/Notifications.php');
include_once(__DIR__ . '/CompletedModels/Results.php');
include_once(__DIR__ . '/CompletedModels/ScannedDocument.php');
include_once(__DIR__ . '/CompletedModels/ReferencesValidationSummary.php');
include_once(__DIR__ . '/CompletedModels/ReferencesValidation.php');

include_once(__DIR__ . '/ErrorModels/ErrorDetail.php');
include_once(__DIR__ . '/ErrorModels/Error.php');
include_once(__DIR__ . '/NotificationsModel/Alerts.php');
include_once(__DIR__ . '/ExportModels/Task.php');

include_once(__DIR__ . '/CompletedWebhook.php');
include_once(__DIR__ . '/CreditsCheckedWebhook.php');
include_once(__DIR__ . '/ErrorWebhook.php');
include_once(__DIR__ . '/IndexedWebhook.php');
include_once(__DIR__ . '/ExportCompletedWebhook.php');

include_once(__DIR__ . '/NewResultWebhook.php');

