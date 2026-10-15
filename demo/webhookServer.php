<?php
require_once __DIR__ . '/vendor/autoload.php';
include_once('../autoload.php');
include_once('./demo.php');
use Copyleaks\CompletedWebhook;
use Copyleaks\CreditsCheckedWebhook;
use Copyleaks\ErrorWebhook;
use Copyleaks\IndexedWebhook;
use Copyleaks\NewResultWebhook;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Factory\AppFactory;


$app = AppFactory::create();
$app->addErrorMiddleware(true, true, true);

$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// Appends the webhook body to logs/<status>.log.
function logWebhook(string $status, $data): void {
    global $logDir;
    $logFile = $logDir . "/$status.log";
    $logContent = date('Y-m-d H:i:s') . "\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    file_put_contents($logFile, $logContent, FILE_APPEND);
}
function parseJsonBody(Request $request): ?array {
    $body = (string) $request->getBody();
    $decoded = json_decode($body, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        return null;
    }
    return $decoded;
}

$app->get('/', function ($request, $response, $args) {
    $response->getBody()->write("Slim is working!");
    return $response;
});

$app->post('/webhook/completed', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    $statusWebhook = CompletedWebhook::fromArray($data);
    logWebhook('completed', $statusWebhook);
    $aiAlert = $statusWebhook->getAIDetectionAlert(); // null when the scan produced no AI alert
    if ($aiAlert !== null) {
        try {
            // null when the alert has no data; throws \JsonException when the data is not valid JSON
            $aiResult = $aiAlert->getAIDetectionResult();
            if ($aiResult !== null && $aiResult->summary !== null) {
                logWebhook('completed-ai-detection', [
                    'modelVersion' => $aiResult->modelVersion,
                    'ai' => $aiResult->summary->ai,
                    'human' => $aiResult->summary->human,
                    'result' => $aiResult
                ]);
            }
        } catch (\JsonException $e) {
            // log it and still acknowledge the webhook with 200 below, so Copyleaks does not retry it
            logWebhook('completed-ai-detection', ['error' => $e->getMessage()]);
        }
    }
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'completed' processed",
        'data' => $statusWebhook 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->post('/webhook/error', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    $error = ErrorWebhook::fromArray($data);

    logWebhook('error', $error);
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'error' processed",
        'data' => $error 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


$app->post('/webhook/creditschecked', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    $credits_checked = CreditsCheckedWebhook::fromArray($data);
    logWebhook('creditsChecked', $credits_checked);
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'creditsChecked' processed",
        'data' => $credits_checked 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->post('/webhook/indexed', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    $indexed = IndexedWebhook::fromArray($data);
    logWebhook('indexed', $indexed);
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'indexed' processed",
        'data' => $indexed 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});

$app->post('/webhook/new-results', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    $new_results = NewResultWebhook::fromArray($data);

    logWebhook('new-results', $new_results);
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'new_results' processed",
        'data' => $new_results 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


$app->run();
