<?php

namespace Copyleaks\Tests;

use Copyleaks\CopyleaksTextModerationConstants;
use Copyleaks\CopyleaksTextModerationLabel;
use Copyleaks\CopyleaksTextModerationLanguages;
use Copyleaks\CopyleaksTextModerationRequestModel;
use Copyleaks\CopyleaksTextModerationResponseModel;
use Copyleaks\CopyleaksWritingAssistantSubmissionModel;
use Copyleaks\ScoreWeights;
use Copyleaks\Tests\Support\StubServer;

/**
 * Text Moderation and Grammar Checker.
 */
class TextAndWritingTest extends SdkTestCase
{
    private function labels(int $count): array
    {
        $labels = [];
        for ($i = 0; $i < $count; $i++) {
            $labels[] = new CopyleaksTextModerationLabel("label-$i");
        }
        return $labels;
    }

    public function testModerationWithoutLanguageDetectsIt(): void
    {
        // A null language used to throw a TypeError.
        $model = new CopyleaksTextModerationRequestModel('Some text to scan.', true, null, [new CopyleaksTextModerationLabel(CopyleaksTextModerationConstants::TOXIC_V1)]);
        $this->assertNull($model->language);

        $this->copyleaks->textModerationClient->submitText($this->token(), 'mod1', $model);
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/text-moderation/mod1/check');
        $this->assertSame(['text' => 'Some text to scan.', 'sandbox' => true, 'labels' => [['id' => 'toxic-v1']]], $body);
    }

    public function testModerationWithLanguage(): void
    {
        $model = new CopyleaksTextModerationRequestModel('Text', true, CopyleaksTextModerationLanguages::ENGLISH, $this->labels(16));
        $this->copyleaks->textModerationClient->submitText($this->token(), 'mod2', $model);
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/text-moderation/mod2/check');
        $this->assertSame('en', $body['language']);
        $this->assertCount(16, $body['labels']);
    }

    public function testModerationLabelLimits(): void
    {
        foreach ([0, 17] as $count) {
            try {
                new CopyleaksTextModerationRequestModel('Text', true, null, $this->labels($count));
                $this->fail("expected $count labels to be rejected");
            } catch (\InvalidArgumentException $e) {
                $this->assertStringContainsString('16', $e->getMessage());
            }
        }
    }

    public function testModerationNamedArguments(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('named arguments through unpacking need PHP 8.1');
        }
        // Same as new CopyleaksTextModerationRequestModel(text: 'Text', sandbox: true, labels: ...), written so PHP 7.4 can parse it.
        $model = new CopyleaksTextModerationRequestModel(...['text' => 'Text', 'sandbox' => true, 'labels' => $this->labels(1)]);
        $this->assertNull($model->language);
        $this->assertTrue($model->sandbox);
    }

    public function testModerationResponseModelVersion(): void
    {
        $data = [
            'modelVersion' => 'v1',
            'moderations' => ['text' => ['chars' => ['labels' => [4], 'starts' => [15], 'lengths' => [4]]]],
            'legend' => [['index' => 4, 'id' => 'toxic-v1']],
            'scannedDocument' => ['scanId' => 'mod1', 'totalWords' => 86, 'totalExcluded' => 0, 'actualCredits' => 1, 'expectedCredits' => 1, 'creationTime' => '2025-08-06T08:05:20Z'],
        ];
        $model = CopyleaksTextModerationResponseModel::fromArray($data);
        $this->assertSame('v1', $model->modelVersion);
        $this->assertSame('toxic-v1', $model->legend[0]->id);

        // The documented example has no legend and fewer scannedDocument fields.
        unset($data['legend'], $data['modelVersion'], $data['scannedDocument']['totalExcluded'], $data['scannedDocument']['actualCredits']);
        $model = CopyleaksTextModerationResponseModel::fromArray($data);
        $this->assertNull($model->modelVersion);
        $this->assertSame([], $model->legend);
        $this->assertSame(86, $model->scannedDocument->totalWords);
    }

    public function testGrammarCheckerRequest(): void
    {
        StubServer::enqueueJson(['score' => ['corrections' => ['overallScore' => 90]]]);
        $submission = new CopyleaksWritingAssistantSubmissionModel('Lions is social.', true, 'en', new ScoreWeights(0.1, null, 0.3));
        $result = $this->copyleaks->writingAssistantClient->submitText($this->token(), 'wa1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v1/writing-feedback/wa1/check');
        $this->assertSame(['text' => 'Lions is social.', 'sandbox' => true, 'language' => 'en',
            'score' => ['grammarScoreWeight' => 0.1, 'sentenceStructureScoreWeight' => 0.3]], $body);
        $this->assertSame(90, $result->score->corrections->overallScore);
    }
}
