<?php

namespace Copyleaks\Tests;

use Copyleaks\CommandException;
use Copyleaks\CopyleaksConfig;
use Copyleaks\CopyleaksFileSubmissionModel;
use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;
use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
use Copyleaks\HttpClientService;
use Copyleaks\RateLimitException;
use Copyleaks\SubmissionProperties;
use Copyleaks\SubmissionWebhooks;
use Copyleaks\Tests\Support\StubServer;
use Copyleaks\UnderMaintenanceException;

/**
 * The HTTP layer: connection reuse, timeouts, headers, JSON encoding and errors.
 */
class HttpClientTest extends SdkTestCase
{
    public function testReusesOneConnectionForConsecutiveRequests(): void
    {
        HttpClientService::closeConnections();
        $this->copyleaks->getReleaseNotes();
        $this->copyleaks->getCreditsBalance($this->token());
        $this->copyleaks->aiDetectionClient->getCreditsBalance($this->token());

        $connections = array_unique(array_column(StubServer::requests(), 'conn'));
        $this->assertCount(3, StubServer::requests());
        $this->assertCount(1, $connections, 'all three requests must use the same keep-alive connection');
    }

    public function testCloseConnectionsOpensANewConnection(): void
    {
        $this->copyleaks->getReleaseNotes();
        HttpClientService::closeConnections();
        $this->copyleaks->getReleaseNotes();

        $requests = StubServer::requests();
        $this->assertNotSame($requests[0]['conn'], $requests[1]['conn']);
    }

    public function testTimeoutsHaveDefaultsAndCanBeConfigured(): void
    {
        CopyleaksConfig::SET_CONNECT_TIMEOUT(CopyleaksConfig::DEFAULT_CONNECT_TIMEOUT);
        CopyleaksConfig::SET_TIMEOUT(CopyleaksConfig::DEFAULT_TIMEOUT);
        $options = HttpClientService::buildOptions('GET', 'https://example.com', []);
        $this->assertSame(10000, $options[CURLOPT_CONNECTTIMEOUT_MS]);
        $this->assertSame(120000, $options[CURLOPT_TIMEOUT_MS]);

        CopyleaksConfig::SET_CONNECT_TIMEOUT(3);
        CopyleaksConfig::SET_TIMEOUT(1.5);
        $options = HttpClientService::buildOptions('GET', 'https://example.com', []);
        $this->assertSame(3000, $options[CURLOPT_CONNECTTIMEOUT_MS]);
        $this->assertSame(1500, $options[CURLOPT_TIMEOUT_MS]);

        CopyleaksConfig::SET_TIMEOUT(0);
        $this->assertSame(0, HttpClientService::buildOptions('GET', 'https://example.com', [])[CURLOPT_TIMEOUT_MS]);

        // A tiny positive value never turns into 0, which curl reads as "no limit".
        CopyleaksConfig::SET_CONNECT_TIMEOUT(0.0001);
        CopyleaksConfig::SET_TIMEOUT(0.0001);
        $options = HttpClientService::buildOptions('GET', 'https://example.com', []);
        $this->assertSame(1, $options[CURLOPT_CONNECTTIMEOUT_MS]);
        $this->assertSame(1, $options[CURLOPT_TIMEOUT_MS]);
    }

    public function testInvalidTimeoutsAreRejected(): void
    {
        foreach ([fn() => CopyleaksConfig::SET_CONNECT_TIMEOUT(0), fn() => CopyleaksConfig::SET_TIMEOUT(-1), fn() => CopyleaksConfig::SET_TIMEOUT('abc')] as $call) {
            try {
                $call();
                $this->fail('expected InvalidArgumentException');
            } catch (\InvalidArgumentException $e) {
                $this->assertNotSame('', $e->getMessage());
            }
        }
    }

    public function testTotalTimeoutStopsAStalledRequest(): void
    {
        CopyleaksConfig::SET_TIMEOUT(1);
        StubServer::enqueue(['delay' => 4, 'body' => '{}']);

        $started = microtime(true);
        try {
            $this->copyleaks->getReleaseNotes();
            $this->fail('expected a timeout');
        } catch (CommandException $e) {
            $elapsed = microtime(true) - $started;
            $this->assertSame(0, $e->getCode());
            $this->assertSame(0, $e->getStatusCode());
            $this->assertStringContainsString('timed out', strtolower($e->getMessage()));
            $this->assertLessThan(3.0, $elapsed);
        }

        // The SDK recovers on the next call.
        CopyleaksConfig::SET_TIMEOUT(20);
        StubServer::enqueueJson(['ok' => true]);
        $this->assertTrue($this->copyleaks->getReleaseNotes()->ok);
    }

    public function testCutOffSuccessBodyThrows(): void
    {
        StubServer::enqueue(['body' => json_encode(['notes' => str_repeat('a', 2000)]), 'truncate' => true]);
        try {
            $this->copyleaks->getReleaseNotes();
            $this->fail('expected a CommandException');
        } catch (CommandException $e) {
            $this->assertSame(0, $e->getStatusCode());
        }

        // The SDK recovers on the next call.
        StubServer::enqueueJson(['ok' => true]);
        $this->assertTrue($this->copyleaks->getReleaseNotes()->ok);
    }

    public function testInvalidJsonSuccessBodyThrows(): void
    {
        StubServer::enqueueJson('{"notes": [');
        try {
            HttpClientService::Execute('GET', StubServer::baseUrl() . '/bad-json', []);
            $this->fail('expected a CommandException');
        } catch (CommandException $e) {
            $this->assertSame(200, $e->getStatusCode());
            $this->assertInstanceOf(\JsonException::class, $e->getPrevious());
            $this->assertSame('{"notes": [', $e->getResponseBody());
        }
    }

    public function testLargeBodyIsSentWithoutExpectContinue(): void
    {
        $content = base64_encode(random_bytes(1500000));
        $submission = new CopyleaksFileSubmissionModel($content, 'big.txt', new SubmissionProperties(new SubmissionWebhooks('https://example.com/{STATUS}')));

        $started = microtime(true);
        $this->copyleaks->submitFile($this->token(), 'big1', $submission);
        $this->assertLessThan(1.0, microtime(true) - $started);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/big1');
        $this->assertSame($content, $body['base64']);
    }

    public function testAcceptsCompressedResponses(): void
    {
        StubServer::enqueue(['body' => json_encode(['notes' => str_repeat('a', 2000)]), 'gzip' => true]);
        $result = $this->copyleaks->getReleaseNotes();

        $this->assertSame(str_repeat('a', 2000), $result->notes);
        $this->assertStringContainsString('gzip', $this->lastRequest()['headers']['accept-encoding'] ?? '');
    }

    public function testCommandExceptionCarriesTheStatusAndBody(): void
    {
        StubServer::enqueueJson('{"error":"bad scan id"}', 400, ['X-Request-Id' => 'abc']);
        try {
            $this->copyleaks->getCreditsBalance($this->token());
            $this->fail('expected CommandException');
        } catch (CommandException $e) {
            $this->assertSame(400, $e->getCode());
            $this->assertSame(400, $e->getStatusCode());
            $this->assertSame('{"error":"bad scan id"}', $e->getResponseBody());
            $this->assertSame('abc', $e->getResponseHeader('X-Request-Id'));
            $this->assertSame('abc', $e->getResponseHeaders()['x-request-id']);
            // The message keeps its earlier format.
            $this->assertStringContainsString('----Copyleaks SDK Error-----', $e->getMessage());
            $this->assertStringContainsString('"http_code":400', $e->getMessage());
            $this->assertStringContainsString('bad scan id', $e->getMessage());
        }
    }

    public function testRateLimitExceptionCarriesStatusAndRetryAfter(): void
    {
        StubServer::enqueueJson('{"error":"slow down"}', 429, ['Retry-After' => '30']);
        try {
            $this->copyleaks->getCreditsBalance($this->token());
            $this->fail('expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertSame(429, $e->getCode());
            $this->assertSame(429, $e->getStatusCode());
            $this->assertSame(30, $e->getRetryAfterSeconds());
            $this->assertSame('{"error":"slow down"}', $e->getResponseBody());
        }
    }

    public function testRetryAfterAsHttpDate(): void
    {
        StubServer::enqueueJson('{}', 429, ['Retry-After' => gmdate('D, d M Y H:i:s', time() + 120) . ' GMT']);
        try {
            $this->copyleaks->getCreditsBalance($this->token());
            $this->fail('expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertGreaterThanOrEqual(100, $e->getRetryAfterSeconds());
            $this->assertLessThanOrEqual(120, $e->getRetryAfterSeconds());
        }
    }

    public function testUnderMaintenanceExceptionCarriesTheStatus(): void
    {
        StubServer::enqueueJson('{}', 503);
        try {
            $this->copyleaks->getReleaseNotes();
            $this->fail('expected UnderMaintenanceException');
        } catch (UnderMaintenanceException $e) {
            $this->assertSame(503, $e->getCode());
            $this->assertSame(503, $e->getStatusCode());
            $this->assertNull($e->getRetryAfterSeconds());
        }
    }

    public function testHeaderMapsAndHeaderLinesAreBothSent(): void
    {
        HttpClientService::Execute('GET', StubServer::baseUrl() . '/map', ['X-From-Map' => 'one', 'X-From-Line: two']);
        $request = $this->lastRequest();
        $this->assertSame('one', $request['headers']['x-from-map']);
        $this->assertSame('two', $request['headers']['x-from-line']);
        $this->assertArrayNotHasKey('expect', $request['headers']);
    }

    public function testUserTextWithJsonNullIsSentIntact(): void
    {
        $text = 'config = {"retries":null, "x": 1} and {"x":null} and "y":null, end';
        $this->copyleaks->writingAssistantClient->submitText($this->token(), 'nulls1', new CopyleaksWritingAssistantSubmissionModel($text, true));
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/writing-feedback/nulls1/check');
        $this->assertSame($text, $body['text']);
        $this->assertSame(['text' => $text, 'sandbox' => true], $body);

        $long = str_repeat('Lions live in prides. ', 15) . '{"x":null}';
        $this->copyleaks->aiDetectionClient->submitNaturalLanguage($this->token(), 'nulls2', new CopyleaksNaturalLanguageSubmissionModel($long, true));
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v2/writer-detector/nulls2/check');
        $this->assertSame($long, $body['text']);
    }

    public function testNonAsciiTextIsSentAsUtf8(): void
    {
        $text = 'Les lions vivent en groupe: été, naïve, 日本語, emoji 🦁, slash / done';
        $this->copyleaks->writingAssistantClient->submitText($this->token(), 'utf8', new CopyleaksWritingAssistantSubmissionModel($text, true));
        $request = $this->lastRequest();
        $this->assertStringContainsString('日本語', $request['body']);
        $this->assertStringContainsString('slash / done', $request['body']);
        $this->assertSame($text, json_decode($request['body'], true)['text']);
    }

    public function testInvalidUtf8ThrowsBeforeSending(): void
    {
        try {
            $this->copyleaks->writingAssistantClient->submitText($this->token(), 'badutf8', new CopyleaksWritingAssistantSubmissionModel("bad \xB1\x31 text", true));
            $this->fail('expected JsonException');
        } catch (\JsonException $e) {
            $this->assertSame([], StubServer::requests());
        }
    }

    public function testModelsAreNotChangedBySubmitting(): void
    {
        $properties = new SubmissionProperties(new SubmissionWebhooks('https://example.com/{STATUS}'), null, null, true);
        $submission = new CopyleaksFileSubmissionModel(base64_encode('hello'), 'a.txt', $properties);
        $this->copyleaks->submitFile($this->token(), 'nochange', $submission);

        // Null properties stay readable after the call (they used to be unset).
        $this->assertNull($properties->includeHtml);
        $this->assertNull($properties->webhooks->newResult);
        $this->assertTrue($properties->sandbox);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/nochange');
        $this->assertSame(['webhooks' => ['status' => 'https://example.com/{STATUS}'], 'sandbox' => true], $body['properties']);
    }

    public function testPathParametersAreEncoded(): void
    {
        $submission = new CopyleaksFileSubmissionModel(base64_encode('hello'), 'a.txt', new SubmissionProperties(new SubmissionWebhooks('https://example.com/{STATUS}')));
        $this->copyleaks->submitFile($this->token(), 'my scan/1?x#y%', $submission);
        $request = $this->lastRequest();
        $this->assertSame('/v3/scans/submit/file/my%20scan%2F1%3Fx%23y%25', $request['path']);
        $this->assertSame('', $request['query']);
    }

    public function testEmptyResponseBodyReturnsEmptyString(): void
    {
        StubServer::enqueue(['status' => 204, 'headers' => [], 'body' => '']);
        $this->assertSame('', HttpClientService::Execute('GET', StubServer::baseUrl() . '/empty', []));
    }

    public function testInvalidVerbIsRejected(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        HttpClientService::Execute('DELETE', StubServer::baseUrl() . '/x', []);
    }
}
