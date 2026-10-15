<?php

namespace Copyleaks\Tests;

use Copyleaks\CommandException;
use Copyleaks\Tests\Support\StubServer;

class LoginTest extends SdkTestCase
{
    public function testLoginSendsJsonWithUserAgent(): void
    {
        StubServer::enqueueJson([
            'access_token' => 'abc123',
            '.issued' => '2026-10-14T10:00:00Z',
            '.expires' => '2026-10-16T10:00:00Z',
        ]);

        $token = $this->copyleaks->login('user@example.com', 'my-key');

        $request = $this->lastRequest();
        $this->assertRequest($request, 'POST', '/v3/account/login/api');
        $this->assertSame('application/json', $request['headers']['content-type']);
        $this->assertSame('application/json', $request['headers']['accept']);
        $this->assertArrayNotHasKey('authorization', $request['headers']);
        $this->assertSame(['email' => 'user@example.com', 'key' => 'my-key'], json_decode($request['body'], true));

        $this->assertSame('abc123', $token->accessToken);
        $this->assertSame('2026-10-16T10:00:00Z', $token->expires);
        $this->assertSame('2026-10-14T10:00:00Z', $token->issued);
    }

    public function testRejectedLoginThrowsWithStatus(): void
    {
        StubServer::enqueueJson('{"error":"invalid credentials"}', 401);
        try {
            $this->copyleaks->login('user@example.com', 'wrong');
            $this->fail('expected CommandException');
        } catch (CommandException $e) {
            $this->assertSame(401, $e->getStatusCode());
            $this->assertStringNotContainsString('wrong', $e->getMessage());
        }
    }
}
