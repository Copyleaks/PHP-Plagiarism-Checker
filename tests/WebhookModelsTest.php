<?php

namespace Copyleaks\Tests;

use Copyleaks\CompletedWebhook;
use Copyleaks\CreditsCheckedWebhook;
use Copyleaks\ErrorWebhook;
use Copyleaks\ExportCompletedWebhook;
use Copyleaks\IndexedWebhook;
use Copyleaks\NewResultWebhook;
use PHPUnit\Framework\TestCase;

/**
 * Webhook models built from decoded webhook bodies.
 */
class WebhookModelsTest extends TestCase
{
    private const INTERNAL_AI = [
        'id' => 'abc', 'title' => 'AI entry', 'introduction' => 'Sample', 'matchedWords' => 40, 'identicalWords' => 30,
        'similarWords' => 6, 'paraphrasedWords' => 4, 'totalWords' => 120,
        'tags' => [['code' => 'internal-ai-source-match', 'title' => 'AI-Generated', 'description' => 'This result contains AI-generated text']],
    ];

    public function testCompletedWebhookNewFields(): void
    {
        $webhook = CompletedWebhook::fromArray([
            'status' => 0,
            'developerPayload' => 'my-payload',
            'scannedDocument' => ['scanId' => 's1', 'totalWords' => 100, 'credits' => 1],
            'results' => ['score' => ['aggregatedScore' => 12.5], 'internet' => [], 'internalAIData' => [self::INTERNAL_AI]],
            'referencesValidation' => ['summary' => ['total' => 12, 'academic' => 8, 'academicValidated' => 6, 'nonAcademicValidated' => 3]],
            'notifications' => ['alerts' => []],
        ]);

        $this->assertSame('my-payload', $webhook->developerPayload);
        $this->assertSame(0, $webhook->status);
        $this->assertSame(12, $webhook->referencesValidation->summary->total);
        $this->assertSame(8, $webhook->referencesValidation->summary->academic);
        $this->assertSame(6, $webhook->referencesValidation->summary->academicValidated);
        $this->assertSame(3, $webhook->referencesValidation->summary->nonAcademicValidated);

        $item = $webhook->results->internalAIData[0];
        $this->assertSame('abc', $item->id);
        $this->assertSame('AI entry', $item->title);
        $this->assertSame('Sample', $item->introduction);
        $this->assertSame(40, $item->matchedWords);
        $this->assertSame(30, $item->identicalWords);
        $this->assertSame(6, $item->similarWords);
        $this->assertSame(4, $item->paraphrasedWords);
        $this->assertSame(120, $item->totalWords);
        $this->assertSame('internal-ai-source-match', $item->tags[0]->code);
    }

    public function testCompletedWebhookWithoutNewFields(): void
    {
        $webhook = CompletedWebhook::fromArray(['status' => 0, 'results' => ['internet' => []]]);
        $this->assertNull($webhook->referencesValidation);
        $this->assertNull($webhook->results->internalAIData);
        $this->assertSame('', $webhook->developerPayload);
    }

    public function testNewResultWebhookInternalAiData(): void
    {
        $webhook = NewResultWebhook::fromArray(['score' => ['aggregatedScore' => 3], 'internalAIData' => [self::INTERNAL_AI], 'developerPayload' => 'p']);
        $this->assertSame(120, $webhook->internalAIData[0]->totalWords);
        $this->assertSame('p', $webhook->developerPayload);
        $this->assertNull(NewResultWebhook::fromArray([])->internalAIData);
    }

    public function testErrorWebhookFields(): void
    {
        $webhook = ErrorWebhook::fromArray([
            'status' => 1,
            'developerPayload' => 'Custom developer payload',
            'error' => [
                'type' => 'invalid_request_error',
                'id' => 'missing_parameter',
                'code' => 1,
                'message' => 'Bad request.',
                'url' => 'https://docs.copyleaks.com/errors/missing_parameter',
                'details' => [['param' => 'filename', 'message' => 'Required']],
            ],
        ]);
        $this->assertSame(1, $webhook->status);
        $this->assertSame('Custom developer payload', $webhook->developerPayload);
        $this->assertSame('invalid_request_error', $webhook->error->type);
        $this->assertSame('missing_parameter', $webhook->error->id);
        $this->assertSame(1, $webhook->error->code);
        $this->assertSame('Bad request.', $webhook->error->message);
        $this->assertSame('https://docs.copyleaks.com/errors/missing_parameter', $webhook->error->url);
        $this->assertSame('filename', $webhook->error->details[0]->param);
        $this->assertSame('Required', $webhook->error->details[0]->message);

        $minimal = ErrorWebhook::fromArray(['status' => 1, 'error' => ['code' => 6, 'message' => 'x']]);
        $this->assertNull($minimal->error->type);
        $this->assertNull($minimal->error->details);
    }

    public function testOtherWebhooksGetTheSharedFields(): void
    {
        $this->assertSame(2, CreditsCheckedWebhook::fromArray(['status' => 2, 'credits' => 5, 'developerPayload' => 'a'])->status);
        $this->assertSame('a', CreditsCheckedWebhook::fromArray(['status' => 2, 'developerPayload' => 'a'])->developerPayload);
        $this->assertSame(3, IndexedWebhook::fromArray(['status' => 3])->status);
        $this->assertSame('b', ExportCompletedWebhook::fromArray(['completed' => true, 'tasks' => [], 'developerPayload' => 'b'])->developerPayload);
    }
}
