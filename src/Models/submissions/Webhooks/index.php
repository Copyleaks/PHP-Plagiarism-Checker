<?php

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

