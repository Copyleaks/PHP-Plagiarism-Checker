<?php

namespace Copyleaks\Tests;

use Copyleaks\CommandException;
use Copyleaks\CopyleaksErrorTypes;
use Copyleaks\ErrorDetail;
use Copyleaks\RateLimitException;
use Copyleaks\Tests\Support\StubServer;
use Copyleaks\UnderMaintenanceException;

/**
 * The API error body, {"error": {...}}, read from the exceptions.
 */
class ApiErrorTest extends SdkTestCase
{
    private const FULL = '{
      "error": {
        "type": "invalid_request_error",
        "id": "missing_parameter",
        "code": 1,
        "message": "Bad request. One or several required parameters are missing or incorrect.",
        "url": "https://docs.copyleaks.com/using-the-apis/api-errors/#error-reference",
        "details": [ { "param": "labels", "message": "Id Label EXAMPLE-v1 is not supported" } ]
      }
    }';

    private function exception(?string $body): CommandException
    {
        return new CommandException('the message', 400, null, $body);
    }

    private function assertNoError(CommandException $e, string $case): void
    {
        $this->assertNull($e->getErrorType(), $case);
        $this->assertNull($e->getErrorId(), $case);
        $this->assertNull($e->getErrorCode(), $case);
        $this->assertNull($e->getErrorMessage(), $case);
        $this->assertNull($e->getErrorUrl(), $case);
        $this->assertSame([], $e->getErrorDetails(), $case);
        $this->assertSame('the message', $e->getMessage(), $case);
        $this->assertSame(400, $e->getStatusCode(), $case);
    }

    public function testFullErrorBody(): void
    {
        $e = $this->exception(self::FULL);
        $this->assertSame(CopyleaksErrorTypes::INVALID_REQUEST_ERROR, $e->getErrorType());
        $this->assertSame('missing_parameter', $e->getErrorId());
        $this->assertSame(1, $e->getErrorCode());
        $this->assertSame('Bad request. One or several required parameters are missing or incorrect.', $e->getErrorMessage());
        $this->assertSame('https://docs.copyleaks.com/using-the-apis/api-errors/#error-reference', $e->getErrorUrl());
        $this->assertEquals([new ErrorDetail('labels', 'Id Label EXAMPLE-v1 is not supported')], $e->getErrorDetails());
        $this->assertSame('the message', $e->getMessage(), 'the message is unchanged');
        $this->assertSame(400, $e->getCode());
        $this->assertSame(self::FULL, $e->getResponseBody());
    }

    public function testMissingOptionalFields(): void
    {
        $e = $this->exception('{"error":{"type":"payment_error","id":"insufficient_credits","code":3,"message":"m"}}');
        $this->assertSame(CopyleaksErrorTypes::PAYMENT_ERROR, $e->getErrorType());
        $this->assertSame('insufficient_credits', $e->getErrorId());
        $this->assertSame(3, $e->getErrorCode());
        $this->assertSame('m', $e->getErrorMessage());
        $this->assertNull($e->getErrorUrl());
        $this->assertSame([], $e->getErrorDetails());

        $this->assertNoError($this->exception('{"error":{}}'), 'empty error object');

        $wrongTypes = $this->exception('{"error":{"type":7,"id":["x"],"message":{"a":1},"url":true,"details":null}}');
        $this->assertNoError($wrongTypes, 'fields that are not strings');

        $unknown = $this->exception('{"error":{"type":"some_future_error"}}');
        $this->assertSame('some_future_error', $unknown->getErrorType(), 'an unknown type is returned as is');
    }

    public function testCodeAsNumberOrNumericString(): void
    {
        $codes = [
            '42' => 42, '"42"' => 42, '"042"' => 42, '"-3"' => -3, '"+7"' => 7, '"0"' => 0, '0' => 0,
            '"9223372036854775807"' => PHP_INT_MAX,
            '" 1"' => 1, '"1\n"' => 1, '"\t-5 "' => -5,
            '"abc"' => null, '""' => null, '" "' => null, '"1 2"' => null, '"- 1"' => null, '"1.5"' => null, '"1e3"' => null,
            '1.5' => null, '1.0' => null, 'true' => null, 'null' => null, '[1]' => null, '{"v":1}' => null,
            '"99999999999999999999"' => null, '99999999999999999999' => null,
        ];
        foreach ($codes as $json => $expected) {
            $this->assertSame($expected, $this->exception('{"error":{"code":' . $json . '}}')->getErrorCode(), "code $json");
        }
    }

    public function testBodyThatIsNotJson(): void
    {
        // The last one is valid JSON that PHP cannot decode into an object (a property name starting with NUL):
        // the getters must not throw.
        foreach ([null, '', '<html><body>502 Bad Gateway</body></html>', '{"error":', 'not json', "\xff\xfe",
            '{"\u0000a":1,"error":{"type":"api_error"}}'] as $body) {
            $this->assertNoError($this->exception($body), var_export($body, true));
        }
    }

    public function testJsonWithoutAnErrorObject(): void
    {
        $bodies = ['{}', '{"message":"x"}', '{"error":"bad scan id"}', '{"error":null}', '{"error":[{"type":"api_error"}]}',
            '[{"error":{"type":"api_error"}}]', '"error"', '42', 'null', '{"Error":{"type":"api_error"}}',
            '{"data":{"error":{"type":"api_error"}}}'];
        foreach ($bodies as $body) {
            $this->assertNoError($this->exception($body), $body);
        }
        $this->assertNoError($this->exception(str_repeat('[', 600) . str_repeat(']', 600)), 'too deep');
    }

    public function testMalformedDetails(): void
    {
        foreach (['"oops"', '7', 'true', '{"param":"labels","message":"m"}', '[]'] as $details) {
            $e = $this->exception('{"error":{"type":"invalid_request_error","details":' . $details . '}}');
            $this->assertSame([], $e->getErrorDetails(), "details $details");
            $this->assertSame('invalid_request_error', $e->getErrorType(), "details $details");
        }

        $e = $this->exception('{"error":{"details":[1,"s",null,[],[{"param":"x"}],{"param":"labels","message":5},{"message":"m"},{},{"param":"p","message":"q","extra":1}]}}');
        $this->assertEquals([
            new ErrorDetail('labels', null),
            new ErrorDetail(null, 'm'),
            new ErrorDetail(null, null),
            new ErrorDetail('p', 'q'),
        ], $e->getErrorDetails());
    }

    public function testErrorTypeConstants(): void
    {
        $this->assertSame('invalid_request_error', CopyleaksErrorTypes::INVALID_REQUEST_ERROR);
        $this->assertSame('authentication_error', CopyleaksErrorTypes::AUTHENTICATION_ERROR);
        $this->assertSame('payment_error', CopyleaksErrorTypes::PAYMENT_ERROR);
        $this->assertSame('api_error', CopyleaksErrorTypes::API_ERROR);
    }

    public function testEveryApiExceptionReadsTheErrorBody(): void
    {
        StubServer::enqueueJson('{"error":{"type":"authentication_error","id":"invalid_token","code":"2","message":"Unauthorized"}}', 401);
        try {
            $this->copyleaks->getCreditsBalance($this->token());
            $this->fail('expected CommandException');
        } catch (CommandException $e) {
            $this->assertSame(401, $e->getStatusCode());
            $this->assertSame(CopyleaksErrorTypes::AUTHENTICATION_ERROR, $e->getErrorType());
            $this->assertSame('invalid_token', $e->getErrorId());
            $this->assertSame(2, $e->getErrorCode());
            $this->assertSame('Unauthorized', $e->getErrorMessage());
            $this->assertStringContainsString('----Copyleaks SDK Error-----', $e->getMessage(), 'the message keeps its format');
        }

        StubServer::enqueueJson('{"error":{"type":"api_error","id":"too_many_requests","code":12}}', 429, ['Retry-After' => '5']);
        try {
            $this->copyleaks->getCreditsBalance($this->token());
            $this->fail('expected RateLimitException');
        } catch (RateLimitException $e) {
            $this->assertSame(5, $e->getRetryAfterSeconds());
            $this->assertSame(CopyleaksErrorTypes::API_ERROR, $e->getErrorType());
            $this->assertSame('too_many_requests', $e->getErrorId());
            $this->assertSame(12, $e->getErrorCode());
            $this->assertSame('Too many requests. Please wait before calling again.', $e->getMessage());
        }

        StubServer::enqueue(['status' => 503, 'headers' => ['Content-Type' => 'text/html'], 'body' => '<html>down</html>']);
        try {
            $this->copyleaks->getReleaseNotes();
            $this->fail('expected UnderMaintenanceException');
        } catch (UnderMaintenanceException $e) {
            $this->assertSame(503, $e->getStatusCode());
            $this->assertNull($e->getErrorType());
            $this->assertSame([], $e->getErrorDetails());
        }

        $this->assertNull((new RateLimitException())->getErrorId(), 'built without a response');
        $this->assertSame('missing_parameter', (new UnderMaintenanceException(503, null, self::FULL))->getErrorId());
    }
}
