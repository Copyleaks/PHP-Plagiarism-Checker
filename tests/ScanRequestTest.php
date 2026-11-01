<?php

namespace Copyleaks\Tests;

use Copyleaks\AuthExipredException;
use Copyleaks\CopyleaksAiImageDetectionAsyncRequestModel;
use Copyleaks\CopyleaksAiImageDetectionRequestModel;
use Copyleaks\CopyleaksAiVideoDetectionRequestModel;
use Copyleaks\CopyleaksAsyncWebhooks;
use Copyleaks\CopyleaksAuthToken;
use Copyleaks\CopyleaksImagePlagiarismRequestModel;
use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;
use Copyleaks\CopyleaksTextModerationLabel;
use Copyleaks\CopyleaksTextModerationRequestModel;
use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
use Copyleaks\Tests\Support\StubServer;

/**
 * The scan methods of the product clients share their checks, URL and headers.
 */
class ScanRequestTest extends SdkTestCase
{
    /**
     * @return array<string, array{string, string, callable}> Method, path with %s for the scan ID, call(token, scanId).
     */
    private function scanCalls(): array
    {
        $hooks = new CopyleaksAsyncWebhooks('https://example.com/hook');
        return [
            'ai text' => ['POST', '/v2/writer-detector/%s/check', function ($token, $scanId) {
                return $this->copyleaks->aiDetectionClient->submitNaturalLanguage($token, $scanId, new CopyleaksNaturalLanguageSubmissionModel('text'));
            }],
            'ai image' => ['POST', '/v1/ai-image-detector/%s/check', function ($token, $scanId) {
                return $this->copyleaks->aiImageDetectionClient->submit($token, $scanId, new CopyleaksAiImageDetectionRequestModel('aW1n', 'a.png', 'ai-image-1-ultra'));
            }],
            'ai image async' => ['PUT', '/v1/ai-image-detector-async/%s/submit', function ($token, $scanId) use ($hooks) {
                return $this->copyleaks->aiImageDetectionClient->submitAsync($token, $scanId,
                    new CopyleaksAiImageDetectionAsyncRequestModel('https://example.com/a.png', 'a.png', 'ai-image-1-ultra', $hooks));
            }],
            'ai video' => ['POST', '/v1/ai-video-detector/%s/submit', function ($token, $scanId) use ($hooks) {
                return $this->copyleaks->aiVideoDetectionClient->submit($token, $scanId,
                    new CopyleaksAiVideoDetectionRequestModel('https://example.com/v.mp4', 'v.mp4', 'ai-video-1-pro', $hooks));
            }],
            'image plagiarism' => ['POST', '/v1/image-plagiarism-detector/%s/check', function ($token, $scanId) {
                return $this->copyleaks->imagePlagiarismClient->submit($token, $scanId, CopyleaksImagePlagiarismRequestModel::fromContent('img', 'a.png'));
            }],
            'text moderation' => ['POST', '/v1/text-moderation/%s/check', function ($token, $scanId) {
                return $this->copyleaks->textModerationClient->submitText($token, $scanId, new CopyleaksTextModerationRequestModel('text', true, null, [new CopyleaksTextModerationLabel('toxic-v1')]));
            }],
            'writing assistant' => ['POST', '/v1/writing-feedback/%s/check', function ($token, $scanId) {
                return $this->copyleaks->writingAssistantClient->submitText($token, $scanId, new CopyleaksWritingAssistantSubmissionModel('text'));
            }],
        ];
    }

    public function testSendsToTheEncodedScanPathWithTheToken(): void
    {
        foreach ($this->scanCalls() as $name => [$method, $path, $call]) {
            $this->assertEquals(new \stdClass(), $call($this->token(), 'my scan/1'), $name);
            $request = $this->lastRequest();
            $this->assertRequest($request, $method, sprintf($path, 'my%20scan%2F1'));
            $this->assertSame('Bearer ' . self::TOKEN, $request['headers']['authorization'] ?? null, $name);
            $multipart = $name === 'image plagiarism';
            $this->assertSame($multipart, strpos($request['headers']['content-type'] ?? '', 'multipart/form-data') === 0, $name);
            $this->assertSame(!$multipart, ($request['headers']['content-type'] ?? '') === 'application/json', $name);
        }
        $this->assertCount(7, StubServer::requests());
    }

    public function testEmptyScanIdIsRejectedBeforeSending(): void
    {
        foreach ($this->scanCalls() as $name => [, , $call]) {
            try {
                $call($this->token(), '');
                $this->fail("$name: expected InvalidArgumentException");
            } catch (\InvalidArgumentException $e) {
                $this->assertSame('Invalid scanId', $e->getMessage(), $name);
            }
        }
        $this->assertSame([], StubServer::requests());
    }

    public function testExpiredTokenIsRejectedBeforeSending(): void
    {
        $expired = new CopyleaksAuthToken(gmdate('Y-m-d\TH:i:s\Z', time() + 60), self::TOKEN, gmdate('Y-m-d\TH:i:s\Z'));
        foreach ($this->scanCalls() as $name => [, , $call]) {
            try {
                $call($expired, 'scan1');
                $this->fail("$name: expected AuthExipredException");
            } catch (AuthExipredException $e) {
                $this->assertInstanceOf(AuthExipredException::class, $e, $name);
            }
        }
        $this->assertSame([], StubServer::requests());
    }

    /**
     * The scan ID and the token are checked before the image is read.
     */
    public function testImagePlagiarismChecksComeBeforeReadingTheImage(): void
    {
        $missing = new CopyleaksImagePlagiarismRequestModel('/no/such/file.png', 'file.png');
        try {
            $this->copyleaks->imagePlagiarismClient->submit($this->token(), '', $missing);
            $this->fail('expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Invalid scanId', $e->getMessage());
        }
        $this->expectException(AuthExipredException::class);
        $this->copyleaks->imagePlagiarismClient->submit(new CopyleaksAuthToken('2000-01-01T00:00:00Z', self::TOKEN, '2000-01-01T00:00:00Z'), 'img1', $missing);
    }
}
