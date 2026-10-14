<?php

namespace Copyleaks\Tests;

use Copyleaks\CopyleaksAiImageDetectionAsyncRequestModel;
use Copyleaks\CopyleaksAiImageDetectionModels;
use Copyleaks\CopyleaksAiImageDetectionRequestModel;
use Copyleaks\CopyleaksAiImageDetectionResponseModel;
use Copyleaks\CopyleaksAiImageMaskTypes;
use Copyleaks\CopyleaksAiImageObjectDetectionItem;
use Copyleaks\CopyleaksAiTextDetectionResponseModel;
use Copyleaks\CopyleaksAiVideoDetectionModels;
use Copyleaks\CopyleaksAiVideoDetectionRequestModel;
use Copyleaks\CopyleaksAiVideoDetectionResponseModel;
use Copyleaks\CopyleaksAsyncWebhooks;
use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;
use Copyleaks\Tests\Support\StubServer;

/**
 * AI Text, AI Image (sync and async) and AI Video Detection.
 */
class AiDetectionTest extends SdkTestCase
{
    private const TEXT = 'Lions are social animals, living in groups called prides, typically consisting of several females, their offspring, and a few males. Female lions are the primary hunters, working together to catch prey. Lions are known for their strength, teamwork, and complex social structures.';

    public function testAiTextSendsExplainAndSensitivity(): void
    {
        $submission = new CopyleaksNaturalLanguageSubmissionModel(self::TEXT, true, 'en', true, 3);
        $this->copyleaks->aiDetectionClient->submitNaturalLanguage($this->token(), 'ait1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v2/writer-detector/ait1/check');
        ksort($body);
        $this->assertSame(['explain' => true, 'language' => 'en', 'sandbox' => true, 'sensitivity' => 3, 'text' => self::TEXT], $body);
    }

    public function testAiTextLeavesUnsetOptionsOut(): void
    {
        $this->copyleaks->aiDetectionClient->submitNaturalLanguage($this->token(), 'ait2', new CopyleaksNaturalLanguageSubmissionModel(self::TEXT));
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v2/writer-detector/ait2/check');
        ksort($body);
        $this->assertSame(['sandbox' => false, 'text' => self::TEXT], $body);
    }

    public function testAiTextCredits(): void
    {
        StubServer::enqueueJson(['credits' => 382]);
        $result = $this->copyleaks->aiDetectionClient->getCreditsBalance($this->token());
        $this->assertAuthGet($this->lastRequest(), '/v2/writer-detector/credits');
        $this->assertSame(382, $result->credits);
    }

    public function testAiTextResponseScannedDocument(): void
    {
        $model = CopyleaksAiTextDetectionResponseModel::fromArray([
            'modelVersion' => 'v11.0',
            'results' => [],
            'summary' => ['human' => 0.0, 'ai' => 1.0],
            'scannedDocument' => ['scanId' => 'ait1', 'totalWords' => 50, 'totalExcluded' => 2, 'actualCredits' => 1, 'expectedCredits' => 1, 'creationTime' => '2026-10-14T10:00:00Z'],
        ]);
        $this->assertSame('ait1', $model->scannedDocument->scanId);
        $this->assertSame(50, $model->scannedDocument->totalWords);
        $this->assertSame(2, $model->scannedDocument->totalExcluded);
        $this->assertSame(1, $model->scannedDocument->actualCredits);
        $this->assertSame(1, $model->scannedDocument->expectedCredits);
        $this->assertSame('2026-10-14T10:00:00Z', $model->scannedDocument->creationTime);
        $this->assertNull(CopyleaksAiTextDetectionResponseModel::fromArray(['results' => []])->scannedDocument);
    }

    public function testAiImageSendsMaskTypeAndObjectDetection(): void
    {
        $submission = new CopyleaksAiImageDetectionRequestModel('aW1n', 'photo.png', CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA, true,
            CopyleaksAiImageMaskTypes::HEATMAP, [new CopyleaksAiImageObjectDetectionItem('face'), new CopyleaksAiImageObjectDetectionItem('hand')]);
        $this->copyleaks->aiImageDetectionClient->submit($this->token(), 'aii1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/ai-image-detector/aii1/check');
        $this->assertSame([
            'base64' => 'aW1n',
            'fileName' => 'photo.png',
            'model' => 'ai-image-1-ultra',
            'sandbox' => true,
            'maskType' => 'heatmap',
            'objectDetection' => [['object' => 'face'], ['object' => 'hand']],
        ], $body);
        $this->assertSame($body, json_decode($submission->toJson(), true));
    }

    public function testAiImageWithoutNewOptionsIsUnchanged(): void
    {
        $submission = new CopyleaksAiImageDetectionRequestModel('aW1n', 'photo.png', CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA, true);
        $this->copyleaks->aiImageDetectionClient->submit($this->token(), 'aii2', $submission);
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/ai-image-detector/aii2/check');
        $this->assertSame(['base64' => 'aW1n', 'fileName' => 'photo.png', 'model' => 'ai-image-1-ultra', 'sandbox' => true], $body);
        $this->assertSame($body, $submission->toArray());
    }

    public function testAiImageResponseNewFields(): void
    {
        $model = CopyleaksAiImageDetectionResponseModel::fromArray([
            'model' => 'ai-image-1-ultra',
            'result' => ['starts' => [0, 512], 'lengths' => [256, 512], 'labels' => [4, 2]],
            'summary' => ['human' => 0.3, 'ai' => 0.7],
            'isAiDetected' => true,
            'imageInfo' => ['shape' => ['height' => 600, 'width' => 800], 'metadata' => ['issuedBy' => 'OpenAI', 'contentSummary' => 'Created using generative AI']],
            'detectedObjects' => [['object' => 'face', 'mask' => ['starts' => [1024], 'lengths' => [512]]]],
            'scannedDocument' => ['scanId' => 'aii1', 'actualCredits' => 1, 'expectedCredits' => 1, 'creationTime' => '2026-10-14T10:00:00Z'],
        ]);
        $this->assertSame([4, 2], $model->result->labels);
        $this->assertTrue($model->isAiDetected);
        $this->assertSame('Created using generative AI', $model->imageInfo->metadata->contentSummary);
        $this->assertCount(1, $model->detectedObjects);
        $this->assertSame('face', $model->detectedObjects[0]->object);
        $this->assertSame([1024], $model->detectedObjects[0]->mask->starts);
        $this->assertSame([512], $model->detectedObjects[0]->mask->lengths);

        $plain = CopyleaksAiImageDetectionResponseModel::fromArray(['model' => 'm', 'summary' => ['human' => 1, 'ai' => 0]]);
        $this->assertNull($plain->isAiDetected);
        $this->assertNull($plain->detectedObjects);
        $this->assertNull($plain->result);
    }

    public function testAiImageAsyncSubmit(): void
    {
        StubServer::enqueueJson('{}', 201);
        $submission = new CopyleaksAiImageDetectionAsyncRequestModel(
            'https://example.com/image1.png',
            'image1.png',
            CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA,
            new CopyleaksAsyncWebhooks('https://example.com/webhook/{STATUS}', [['Authorization', 'Bearer hook']]),
            true,
            'order-1',
            CopyleaksAiImageMaskTypes::HEATMAP,
            [new CopyleaksAiImageObjectDetectionItem('face')],
            [['X-Custom-Auth', 'tok']]
        );
        $this->copyleaks->aiImageDetectionClient->submitAsync($this->token(), 'aia1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v1/ai-image-detector-async/aia1/submit');
        $this->assertSame([
            'url' => 'https://example.com/image1.png',
            'fileName' => 'image1.png',
            'model' => 'ai-image-1-ultra',
            'webhooks' => ['url' => 'https://example.com/webhook/{STATUS}', 'headers' => [['Authorization', 'Bearer hook']]],
            'sandbox' => true,
            'developerPayload' => 'order-1',
            'maskType' => 'heatmap',
            'objectDetection' => [['object' => 'face']],
            'headers' => [['X-Custom-Auth', 'tok']],
        ], $body);
    }

    public function testAiImageAsyncMinimalSubmit(): void
    {
        StubServer::enqueueJson('{}', 201);
        $submission = new CopyleaksAiImageDetectionAsyncRequestModel('https://example.com/i.png', 'i.png', 'ai-image-1-ultra', new CopyleaksAsyncWebhooks('https://example.com/hook'));
        $this->copyleaks->aiImageDetectionClient->submitAsync($this->token(), 'aia2', $submission);
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v1/ai-image-detector-async/aia2/submit');
        $this->assertSame(['url' => 'https://example.com/i.png', 'fileName' => 'i.png', 'model' => 'ai-image-1-ultra', 'webhooks' => ['url' => 'https://example.com/hook']], $body);
    }

    public function testAiVideoSubmit(): void
    {
        StubServer::enqueueJson('{}', 201);
        $submission = new CopyleaksAiVideoDetectionRequestModel(
            'https://example.com/my-video.mp4',
            'my-video.mp4',
            CopyleaksAiVideoDetectionModels::AI_VIDEO_1_PRO,
            new CopyleaksAsyncWebhooks('https://example.com/webhook/receive-results', [['Authorization', 'Bearer hook']]),
            true,
            'order-id-12345',
            [['X-Custom-Auth', 'my-token']],
            'GET'
        );
        $result = $this->copyleaks->aiVideoDetectionClient->submit($this->token(), 'vid1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/ai-video-detector/vid1/submit');
        $this->assertSame([
            'url' => 'https://example.com/my-video.mp4',
            'filename' => 'my-video.mp4',
            'model' => 'ai-video-1-pro',
            'webhooks' => ['url' => 'https://example.com/webhook/receive-results', 'headers' => [['Authorization', 'Bearer hook']]],
            'sandbox' => true,
            'developerPayload' => 'order-id-12345',
            'headers' => [['X-Custom-Auth', 'my-token']],
            'verb' => 'GET',
        ], $body);
        $this->assertEquals(new \stdClass(), $result);
    }

    public function testAiVideoMinimalSubmit(): void
    {
        StubServer::enqueueJson('{}', 201);
        $submission = new CopyleaksAiVideoDetectionRequestModel('https://example.com/v.mp4', 'v.mp4', 'ai-video-1-pro', new CopyleaksAsyncWebhooks('https://example.com/hook'));
        $this->copyleaks->aiVideoDetectionClient->submit($this->token(), 'vid2', $submission);
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/ai-video-detector/vid2/submit');
        $this->assertSame(['url' => 'https://example.com/v.mp4', 'filename' => 'v.mp4', 'model' => 'ai-video-1-pro', 'webhooks' => ['url' => 'https://example.com/hook']], $body);
    }

    public function testAiVideoWebhookPayload(): void
    {
        $payload = json_decode('{
          "model": "ai-video-1-pro",
          "audioResult": {"starts": [13000, 45000, 47000], "lengths": [14000, 1000, 8700],
            "exclude": {"starts": [0, 3250], "lengths": [2950, 1500]}},
          "visualResult": {"starts": [11566, 29433], "lengths": [6134, 26267], "exclude": {"starts": [], "lengths": []}},
          "summary": {"audioAIRatio": 0.4902, "visualAIRatio": 0.5817, "overallAIRatio": 0.7487},
          "isAiDetected": true,
          "videoInfo": {"metadata": {"issuedTime": "2026-03-17T13:14:57+00:00", "issuedBy": "OpenAI", "appOrDeviceUsed": "Sora",
            "contentSummary": "Created using generative AI"}, "duration": 55.7},
          "scannedVideo": {"scanId": "my-video-scan-1", "actualCredits": 1, "expectedCredits": 1, "creationTime": "2026-05-05T12:37:50Z"}
        }', true);
        $model = CopyleaksAiVideoDetectionResponseModel::fromArray($payload);

        $this->assertSame('ai-video-1-pro', $model->model);
        $this->assertSame([13000, 45000, 47000], $model->audioResult->starts);
        $this->assertSame([14000, 1000, 8700], $model->audioResult->lengths);
        $this->assertSame([0, 3250], $model->audioResult->exclude->starts);
        $this->assertSame([2950, 1500], $model->audioResult->exclude->lengths);
        $this->assertSame([], $model->visualResult->exclude->starts);
        $this->assertSame(0.4902, $model->summary->audioAIRatio);
        $this->assertSame(0.5817, $model->summary->visualAIRatio);
        $this->assertSame(0.7487, $model->summary->overallAIRatio);
        $this->assertTrue($model->isAiDetected);
        $this->assertSame(55.7, $model->videoInfo->duration);
        $this->assertSame('Sora', $model->videoInfo->metadata->appOrDeviceUsed);
        $this->assertSame('Created using generative AI', $model->videoInfo->metadata->contentSummary);
        $this->assertSame('my-video-scan-1', $model->scannedVideo->scanId);
        $this->assertSame(1, $model->scannedVideo->actualCredits);
        $this->assertSame('2026-05-05T12:37:50Z', $model->scannedVideo->creationTime);

        $empty = CopyleaksAiVideoDetectionResponseModel::fromArray(['summary' => 'not an object']);
        $this->assertNull($empty->summary);
        $this->assertNull($empty->audioResult);
        $this->assertNull(CopyleaksAiVideoDetectionResponseModel::fromArray(null));
    }
}
