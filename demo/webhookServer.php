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
// Enable error reporting (optional but useful)
$app->addErrorMiddleware(true, true, true);

$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0777, true);
}

// writes the response from webhook to logs directory with file name as the webhook status
function logWebhook(string $status, $data): void {
    global $logDir;
    $logFile = $logDir . "/$status.log";
    $logContent = date('Y-m-d H:i:s') . "\n" . json_encode($data, JSON_PRETTY_PRINT) . "\n\n";
    file_put_contents($logFile, $logContent, FILE_APPEND);
}

// parses the json payload
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
    //desrialze the json payload into CompletedWebhook model
    $statusWebhook = CompletedWebhook::fromArray($data);
    #var_dump($statusWebhook);
    logWebhook('completed', $statusWebhook);
    // Create a response array
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
    //desrialze the json payload into ErrorWebhook model
    $error = ErrorWebhook::fromArray($data);

    logWebhook('error', $error);
    // Create a response array
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'error' processed",
        'data' => $error 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


$app->post('/webhook/creditsChecked', function (Request $request, Response $response) {
    $data = parseJsonBody($request);
    if ($data === null) {
        $response->getBody()->write("Invalid JSON");
        return $response->withStatus(400);
    }
    //desrialze the json payload into CreditsCheckedWebhook model
    $credits_checked = CreditsCheckedWebhook::fromArray($data);
    logWebhook('creditsChecked', $credits_checked);
    
    // Create a response array
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

    //desrialze the json payload into IndexedWebhook model
    $indexed = IndexedWebhook::fromArray($data);
    logWebhook('indexed', $indexed);
    
    // Create a response array
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
    //desrialze the json payload into NewResultWebhook model
    $new_results = NewResultWebhook::fromArray($data);

    logWebhook('new-results', $new_results);
    // Create a response array
    $responseData = [
        'status' => 'success',
        'message' => "Webhook 'new_results' processed",
        'data' => $new_results 
    ];
    
    $response->getBody()->write(json_encode($responseData));
    return $response->withHeader('Content-Type', 'application/json')->withStatus(200);
});


$app->run();
