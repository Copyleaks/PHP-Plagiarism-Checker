<?php

namespace Copyleaks\Tests;

use Copyleaks\Copyleaks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksConfig;
use Copyleaks\HttpClientService;
use Copyleaks\Tests\Support\StubServer;
use PHPUnit\Framework\TestCase;

/**
 * Points the SDK at the local stub server and gives helpers to check the requests it sent.
 */
abstract class SdkTestCase extends TestCase
{
    protected const TOKEN = 'test-access-token';
    protected const USER_AGENT = 'User-Agent: php-sdk/' . CopyleaksConfig::SDK_VERSION;

    protected Copyleaks $copyleaks;

    protected function setUp(): void
    {
        StubServer::start();
        StubServer::reset();
        CopyleaksConfig::SET_API_SERVER_URI(StubServer::baseUrl());
        CopyleaksConfig::SET_IDENTITY_SERVER_URI(StubServer::baseUrl());
        CopyleaksConfig::SET_CONNECT_TIMEOUT(5);
        CopyleaksConfig::SET_TIMEOUT(20);
        $this->copyleaks = new Copyleaks();
    }

    protected function tearDown(): void
    {
        CopyleaksConfig::SET_API_SERVER_URI('https://api.copyleaks.com');
        CopyleaksConfig::SET_IDENTITY_SERVER_URI('https://id.copyleaks.com');
        CopyleaksConfig::SET_CONNECT_TIMEOUT(CopyleaksConfig::DEFAULT_CONNECT_TIMEOUT);
        CopyleaksConfig::SET_TIMEOUT(CopyleaksConfig::DEFAULT_TIMEOUT);
    }

    protected function token(): CopyleaksAuthToken
    {
        return new CopyleaksAuthToken(gmdate('Y-m-d\TH:i:s\Z', time() + 3600), self::TOKEN, gmdate('Y-m-d\TH:i:s\Z'));
    }

    protected function lastRequest(): array
    {
        return StubServer::lastRequest();
    }

    /**
     * Asserts the method and path, and that the request has the SDK User-Agent and no "Expect: 100-continue".
     */
    protected function assertRequest(array $request, string $method, string $path, string $query = ''): void
    {
        $this->assertSame($method, $request['method']);
        $this->assertSame($path, $request['path']);
        $this->assertSame($query, $request['query']);
        $this->assertSame('php-sdk/' . CopyleaksConfig::SDK_VERSION, $request['headers']['user-agent'] ?? null);
        $this->assertArrayNotHasKey('expect', $request['headers']);
        $this->assertArrayNotHasKey('transfer-encoding', $request['headers']);
    }

    /**
     * Asserts an authenticated JSON request and returns the decoded body.
     * @return mixed
     */
    protected function assertJsonRequest(array $request, string $method, string $path)
    {
        $this->assertRequest($request, $method, $path);
        $this->assertSame('Bearer ' . self::TOKEN, $request['headers']['authorization'] ?? null);
        $this->assertSame('application/json', $request['headers']['content-type'] ?? null);
        $this->assertSame((string) strlen($request['body']), $request['headers']['content-length'] ?? null);
        return json_decode($request['body'], true, 512, JSON_THROW_ON_ERROR);
    }

    /**
     * Asserts an authenticated GET without a body.
     */
    protected function assertAuthGet(array $request, string $path, string $query = ''): void
    {
        $this->assertRequest($request, 'GET', $path, $query);
        $this->assertSame('Bearer ' . self::TOKEN, $request['headers']['authorization'] ?? null);
        $this->assertSame('', $request['body']);
    }

    /**
     * Asserts a public GET: no Authorization header, no body.
     */
    protected function assertPublicGet(array $request, string $path): void
    {
        $this->assertRequest($request, 'GET', $path);
        $this->assertArrayNotHasKey('authorization', $request['headers']);
        $this->assertSame('', $request['body']);
    }
}
