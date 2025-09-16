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
include_once('baseModels/Metadata.php');
include_once('baseModels/Webhook.php');
include_once('baseModels/StatusWebhook.php');


include_once('ResultsModels/SharedResultsModel.php');
include_once('NewResultsModel/NewResultScore.php');
include_once('NewResultsModel/NewResultsInternet.php');
include_once('NewResultsModel/NewResultsRepositories.php');


include_once('ResultsModels/Batch.php');
include_once('ResultsModels/Internet.php');
include_once('ResultsModels/Database.php');
include_once('ResultsModels/Repositories.php');
include_once('ResultsModels/RepositoryMetadata.php');
include_once('ResultsModels/Score.php');
include_once('ResultsModels/Tags.php');

include_once('CompletedModels/Notifications.php');
include_once('CompletedModels/Results.php');
include_once('CompletedModels/ScannedDocument.php');

include_once('ErrorModels/Error.php');
include_once('NotificationsModel/Alerts.php');
include_once('ExportModels/Task.php');

include_once('CompletedWebhook.php');
include_once('CreditsCheckedWebhook.php');
include_once('ErrorWebhook.php');
include_once('IndexedWebhook.php');
include_once('ExportCompletedWebhook.php');

include_once('NewResultWebhook.php');

