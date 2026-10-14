<?php

namespace Copyleaks\Tests;

use Copyleaks\CopyleaksDeleteRequestModel;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\CopyleaksStartErrorHandlings;
use Copyleaks\CopyleaksStartRequestModel;
use Copyleaks\ExportAiDetection;
use Copyleaks\ExportCrawledVersion;
use Copyleaks\ExportOverview;
use Copyleaks\ExportPdfReport;
use Copyleaks\ExportResults;
use Copyleaks\ExportWritingFeedback;
use Copyleaks\IdObject;
use Copyleaks\Tests\Support\StubServer;

/**
 * Export, start, delete, resend webhook, credits and usage history.
 */
class ScanManagementTest extends SdkTestCase
{
    public function testExportWithOnlyAPdfReport(): void
    {
        $model = new CopyleaksExportModel('https://example.com/export/done', null, null, new ExportPdfReport('https://example.com/pdf', 'POST'));
        $this->copyleaks->export($this->token(), 'scan1', 'export1', $model);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v3/downloads/scan1/export/export1');
        $this->assertSame([
            'completionWebhook' => 'https://example.com/export/done',
            'pdfReport' => ['endpoint' => 'https://example.com/pdf', 'verb' => 'POST'],
        ], $body);
    }

    public function testExportWithEveryTarget(): void
    {
        $model = new CopyleaksExportModel(
            'https://example.com/export/done',
            [new ExportResults('result-1', 'https://example.com/r1', 'POST', [['Authorization', 'Bearer x']])],
            new ExportCrawledVersion('https://example.com/crawled', 'PUT'),
            new ExportPdfReport('https://example.com/pdf', 'POST'),
            5,
            'export-payload',
            [['X-Done', 'yes']],
            new ExportAiDetection('https://example.com/ai', 'POST'),
            new ExportWritingFeedback('https://example.com/wf', 'POST', [['X-WF', '1']]),
            new ExportOverview('https://example.com/overview', 'POST')
        );
        $this->copyleaks->export($this->token(), 'scan2', 'export2', $model);

        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v3/downloads/scan2/export/export2');
        $this->assertSame([
            'completionWebhook' => 'https://example.com/export/done',
            'results' => [['id' => 'result-1', 'endpoint' => 'https://example.com/r1', 'verb' => 'POST', 'headers' => [['Authorization', 'Bearer x']]]],
            'crawledVersion' => ['endpoint' => 'https://example.com/crawled', 'verb' => 'PUT'],
            'maxRetries' => 5,
            'developerPayload' => 'export-payload',
            'pdfReport' => ['endpoint' => 'https://example.com/pdf', 'verb' => 'POST'],
            'completionWebhookHeaders' => [['X-Done', 'yes']],
            'aiDetection' => ['endpoint' => 'https://example.com/ai', 'verb' => 'POST'],
            'writingFeedback' => ['endpoint' => 'https://example.com/wf', 'verb' => 'POST', 'headers' => [['X-WF', '1']]],
            'overview' => ['endpoint' => 'https://example.com/overview', 'verb' => 'POST'],
        ], $body);
    }

    public function testExportKeepsTheOldPositionalSignature(): void
    {
        $model = new CopyleaksExportModel('https://example.com/done', [], new ExportCrawledVersion('https://example.com/c', 'POST'));
        $this->copyleaks->export($this->token(), 'scan3', 'export3', $model);
        $body = $this->assertJsonRequest($this->lastRequest(), 'POST', '/v3/downloads/scan3/export/export3');
        $this->assertSame([], $body['results']);
        $this->assertSame(['endpoint' => 'https://example.com/c', 'verb' => 'POST'], $body['crawledVersion']);
    }

    public function testDeleteWithHeaders(): void
    {
        $model = new CopyleaksDeleteRequestModel([new IdObject('a'), new IdObject('b')], true, 'https://example.com/deleted', [['X-Key', 'v']]);
        $this->copyleaks->delete($this->token(), $model);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PATCH', '/v3.1/scans/delete');
        $this->assertSame([
            'scans' => [['id' => 'a'], ['id' => 'b']],
            'purge' => true,
            'completionWebhook' => 'https://example.com/deleted',
            'headers' => [['X-Key', 'v']],
        ], $body);
    }

    public function testDeleteWithoutWebhookLeavesItOut(): void
    {
        $model = new CopyleaksDeleteRequestModel([new IdObject('a')]);
        $this->copyleaks->delete($this->token(), $model);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PATCH', '/v3.1/scans/delete');
        $this->assertSame(['scans' => [['id' => 'a']], 'purge' => false], $body);
        $this->assertSame('', $model->completionWebhook);
    }

    public function testStartReturnsTheServerResponse(): void
    {
        StubServer::enqueueJson(['success' => ['a'], 'failed' => []]);
        $result = $this->copyleaks->start($this->token(), new CopyleaksStartRequestModel(['a'], CopyleaksStartErrorHandlings::CANCEL));

        $body = $this->assertJsonRequest($this->lastRequest(), 'PATCH', '/v3/scans/start');
        $this->assertSame(['trigger' => ['a'], 'errorHandling' => 0], $body);
        $this->assertSame(['a'], $result->success);
    }

    public function testResendWebhookSendsAnEmptyBody(): void
    {
        StubServer::enqueue(['status' => 200, 'headers' => [], 'body' => '']);
        $this->copyleaks->resendWebhook($this->token(), 'scan9');

        $request = $this->lastRequest();
        $this->assertRequest($request, 'POST', '/v3/scans/scan9/webhooks/resend');
        $this->assertSame('Bearer ' . self::TOKEN, $request['headers']['authorization']);
        $this->assertSame('0', $request['headers']['content-length']);
        $this->assertSame('', $request['body']);
    }

    public function testCreditsBalance(): void
    {
        StubServer::enqueueJson(['Amount' => 42]);
        $result = $this->copyleaks->getCreditsBalance($this->token());
        $this->assertAuthGet($this->lastRequest(), '/v3/scans/credits');
        $this->assertSame(42, $result->Amount);
    }

    public function testUsagesHistory(): void
    {
        StubServer::enqueue(['headers' => ['Content-Type' => 'text/csv'], 'body' => "a,b\n1,2\n"]);
        $csv = $this->copyleaks->getUsagesHistoryCsv($this->token(), '01-09-2026', '30-09-2026');
        $this->assertAuthGet($this->lastRequest(), '/v3/scans/usages/history', 'start=01-09-2026&end=30-09-2026');
        $this->assertSame("a,b\n1,2\n", $csv);
    }
}
