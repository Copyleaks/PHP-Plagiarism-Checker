<?php

namespace Copyleaks\Tests;

use Copyleaks\AIGeneratedText;
use Copyleaks\AIGeneratedTextExplain;
use Copyleaks\AISourceMatch;
use Copyleaks\CopyleaksFileOcrSubmissionModel;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\CopyleaksFileSubmissionModel;
use Copyleaks\CopyleaksURLSubmissionModel;
use Copyleaks\CrossLanguages;
use Copyleaks\ExportPdfReport;
use Copyleaks\InternalAISourceMatch;
use Copyleaks\Language;
use Copyleaks\PdfReportVersion;
use Copyleaks\ReportCustomizationColors;
use Copyleaks\ScoreWeights;
use Copyleaks\SubmissionAssignment;
use Copyleaks\SubmissionCourse;
use Copyleaks\SubmissionExclude;
use Copyleaks\SubmissionFilter;
use Copyleaks\SubmissionIndexing;
use Copyleaks\SubmissionInstitution;
use Copyleaks\SubmissionOverview;
use Copyleaks\SubmissionPDF;
use Copyleaks\SubmissionProperties;
use Copyleaks\SubmissionReferences;
use Copyleaks\SubmissionRepository;
use Copyleaks\SubmissionScanning;
use Copyleaks\SubmissionScanningCopyleaksDB;
use Copyleaks\SubmissionScanningExclude;
use Copyleaks\SubmissionScanningInclude;
use Copyleaks\SubmissionWebhooks;
use Copyleaks\SubmissionWritingFeedback;

/**
 * Plagiarism Checker submit requests (file, OCR, URL) and every scan property.
 */
class ScanSubmissionTest extends SdkTestCase
{
    private function webhooks(): SubmissionWebhooks
    {
        return new SubmissionWebhooks('https://example.com/webhook/{STATUS}');
    }

    public function testEmptyIdsAreRejectedBeforeSending(): void
    {
        $properties = new SubmissionProperties($this->webhooks());
        $export = new CopyleaksExportModel('https://example.com/done', null, null, new ExportPdfReport('https://example.com/pdf', 'POST'));
        $calls = [
            'submitFile' => fn() => $this->copyleaks->submitFile($this->token(), '', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties)),
            'submitFileOcr' => fn() => $this->copyleaks->submitFileOcr($this->token(), '', new CopyleaksFileOcrSubmissionModel('en', 'aGk=', 'a.png', $properties)),
            'submitUrl' => fn() => $this->copyleaks->submitUrl($this->token(), '', new CopyleaksURLSubmissionModel('https://example.com', $properties)),
            'export scanId' => fn() => $this->copyleaks->export($this->token(), '', 'export1', $export),
            'resendWebhook' => fn() => $this->copyleaks->resendWebhook($this->token(), ''),
        ];
        foreach ($calls as $name => $call) {
            try {
                $call();
                $this->fail("$name: expected InvalidArgumentException");
            } catch (\InvalidArgumentException $e) {
                $this->assertSame('Invalid scanId', $e->getMessage(), $name);
            }
        }
        try {
            $this->copyleaks->export($this->token(), 'scan1', '', $export);
            $this->fail('export: expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
            $this->assertSame('Invalid exportId', $e->getMessage());
        }
        $this->assertSame([], \Copyleaks\Tests\Support\StubServer::requests());
    }

    public function testMinimalFileSubmission(): void
    {
        $submission = new CopyleaksFileSubmissionModel(base64_encode('Hello world'), 'hello.txt', new SubmissionProperties($this->webhooks(), null, null, true));
        $this->copyleaks->submitFile($this->token(), 'scan1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/scan1');
        ksort($body);
        $this->assertSame([
            'base64' => base64_encode('Hello world'),
            'filename' => 'hello.txt',
            'properties' => ['webhooks' => ['status' => 'https://example.com/webhook/{STATUS}'], 'sandbox' => true],
        ], $body);
    }

    public function testEveryScanPropertyIsSentUnderItsDocumentedName(): void
    {
        $properties = new SubmissionProperties(
            new SubmissionWebhooks('https://example.com/webhook/{STATUS}', 'https://example.com/new-result', [['X-New', 'n']], [['X-Status', 's']]),
            true,
            'payload-1',
            true,
            48,
            3,
            true,
            1,
            null,
            new SubmissionFilter(true, false, true, 10, true, ['example.com'], 0, true),
            new SubmissionScanning(
                false,
                new SubmissionScanningExclude('skip-*', ['backlinks.example.com'], ['ignore me']),
                [],
                new SubmissionScanningCopyleaksDB(true, false),
                new SubmissionScanningInclude('keep-*'),
                new CrossLanguages([new Language('fr'), new Language('es')])
            ),
            new SubmissionIndexing([new SubmissionRepository('repo1')], true),
            new SubmissionExclude(true, true, false, false, false, true, ['tpl1']),
            new SubmissionPDF(true, 'Report', 'bG9nbw==', false, null, new ReportCustomizationColors(null, '#000001', null, null, null, '#AA0000', '#FF9A02'), 'v3'),
            null,
            1,
            null,
            new AIGeneratedText(true, 3, new AIGeneratedTextExplain(true)),
            'fr',
            new AISourceMatch(true),
            'America/New_York',
            new SubmissionCourse('course-1'),
            new SubmissionAssignment('assignment-1'),
            new SubmissionInstitution('institution-1'),
            new SubmissionWritingFeedback(true, new ScoreWeights(0.5, 1.0, 0.25, 0.0)),
            new SubmissionOverview(true, false, true, false, true),
            new InternalAISourceMatch(true),
            new SubmissionReferences(true)
        );

        $this->copyleaks->submitFile($this->token(), 'full1', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/full1');

        $this->assertSame([
            'webhooks' => [
                'status' => 'https://example.com/webhook/{STATUS}',
                'newResult' => 'https://example.com/new-result',
                'newResultHeaders' => [['X-New', 'n']],
                'statusHeaders' => [['X-Status', 's']],
            ],
            'includeHtml' => true,
            'developerPayload' => 'payload-1',
            'sandbox' => true,
            'expiration' => 48,
            'sensitivityLevel' => 3,
            'cheatDetection' => true,
            'action' => 1,
            'filters' => [
                'identicalEnabled' => true,
                'minorChangesEnabled' => false,
                'relatedMeaningEnabled' => true,
                'minCopiedWords' => 10,
                'safeSearch' => true,
                'domains' => ['example.com'],
                'domainsMode' => 0,
                'allowSameDomain' => true,
            ],
            'scanning' => [
                'internet' => false,
                'exclude' => ['idPattern' => 'skip-*', 'backlinksDomains' => ['backlinks.example.com'], 'text' => ['ignore me']],
                'repositories' => [],
                'copyleaksDb' => ['includeMySubmissions' => true, 'includeOthersSubmissions' => false],
                'include' => ['idPattern' => 'keep-*'],
                'crossLanguages' => ['languages' => [['code' => 'fr'], ['code' => 'es']]],
            ],
            'indexing' => ['repositories' => [['id' => 'repo1']], 'copyleaksDb' => true],
            'exclude' => [
                'quotes' => true,
                'references' => true,
                'tableOfContents' => false,
                'titles' => false,
                'htmlTemplate' => false,
                'citations' => true,
                'documentTemplateIds' => ['tpl1'],
            ],
            'pdf' => [
                'create' => true,
                'title' => 'Report',
                'largeLogo' => 'bG9nbw==',
                'rtl' => false,
                'reportVersion' => 'v3',
                'colors' => ['titles' => '#000001', 'aiDetection' => '#AA0000', 'writingFeedback' => '#FF9A02'],
            ],
            'scanMethodAlgorithm' => 1,
            'aiGeneratedText' => ['detect' => true, 'sensitivity' => 3, 'explain' => ['enable' => true]],
            'displayLanguage' => 'fr',
            'aiSourceMatch' => ['enable' => true],
            'scanTimeZone' => 'America/New_York',
            'course' => ['id' => 'course-1'],
            'assignment' => ['id' => 'assignment-1'],
            'institution' => ['id' => 'institution-1'],
            'writingFeedback' => [
                'enable' => true,
                'score' => ['grammarScoreWeight' => 0.5, 'mechanicsScoreWeight' => 1, 'sentenceStructureScoreWeight' => 0.25, 'wordChoiceScoreWeight' => 0],
            ],
            'overview' => [
                'enable' => true,
                'ignoreAIDetection' => false,
                'ignorePlagiarismDetection' => true,
                'ignoreWritingFeedback' => false,
                'ignoreAuthorData' => true,
            ],
            'internalAiSourceMatch' => ['enable' => true],
            'references' => ['validate' => true],
        ], $body['properties']);
    }

    public function testNamedArgumentsAndPropertyAssignment(): void
    {
        if (PHP_VERSION_ID < 80100) {
            $this->markTestSkipped('named arguments through unpacking need PHP 8.1');
        }
        // Same as new SubmissionProperties(webhooks: ..., sandbox: true, scanTimeZone: 'UTC'), written so PHP 7.4 can parse it.
        $properties = new SubmissionProperties(...['webhooks' => $this->webhooks(), 'sandbox' => true, 'scanTimeZone' => 'UTC']);
        $properties->writingFeedback = new SubmissionWritingFeedback(true);
        $properties->references = new SubmissionReferences(true);

        $this->copyleaks->submitFile($this->token(), 'named1', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/named1');
        $this->assertSame([
            'webhooks' => ['status' => 'https://example.com/webhook/{STATUS}'],
            'sandbox' => true,
            'scanTimeZone' => 'UTC',
            'writingFeedback' => ['enable' => true],
            'references' => ['validate' => true],
        ], $body['properties']);
    }

    public function testFilterDefaultsLeaveServerDefaultsAlone(): void
    {
        $properties = new SubmissionProperties($this->webhooks());
        $properties->filters = new SubmissionFilter();
        $properties->scanning = new SubmissionScanning();
        $properties->scanning->repositories = [];

        $this->copyleaks->submitFile($this->token(), 'defaults1', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/defaults1');

        // minorChangesEnabled and relatedMeaningEnabled (server default true) and scanning.internet (server default true)
        // are no longer sent as false.
        $this->assertSame([
            'identicalEnabled' => true,
            'safeSearch' => false,
            'domains' => [],
            'domainsMode' => 1,
            'allowSameDomain' => false,
        ], $body['properties']['filters']);
        $this->assertSame(['repositories' => []], $body['properties']['scanning']);
        $this->assertNull($properties->filters->minorChangesEnabled);
        $this->assertNull($properties->scanning->internet);
    }

    public function testAllowSameDomainIsSent(): void
    {
        $properties = new SubmissionProperties($this->webhooks());
        $properties->filters = new SubmissionFilter(true, true, true, null, false, [], 1, true);
        $this->assertTrue($properties->filters->allowSameDomain);

        $this->copyleaks->submitUrl($this->token(), 'samedomain', new CopyleaksURLSubmissionModel('https://example.com/page', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/url/samedomain');
        $this->assertTrue($body['properties']['filters']['allowSameDomain']);
    }

    public function testUrlSubmissionWithVerbAndHeaders(): void
    {
        $submission = new CopyleaksURLSubmissionModel('https://example.com/form', new SubmissionProperties($this->webhooks()), 'POST', [['Cookie', 'a=b'], ['X-Token', 't']]);
        $this->copyleaks->submitUrl($this->token(), 'url1', $submission);

        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/url/url1');
        $this->assertSame('https://example.com/form', $body['url']);
        $this->assertSame('POST', $body['verb']);
        $this->assertSame([['Cookie', 'a=b'], ['X-Token', 't']], $body['headers']);
    }

    public function testUrlSubmissionWithoutVerbAndHeaders(): void
    {
        $this->copyleaks->submitUrl($this->token(), 'url2', new CopyleaksURLSubmissionModel('https://example.com', new SubmissionProperties($this->webhooks())));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/url/url2');
        $keys = array_keys($body);
        sort($keys);
        $this->assertSame(['properties', 'url'], $keys);
    }

    public function testOcrSubmission(): void
    {
        $submission = new CopyleaksFileOcrSubmissionModel('en', base64_encode('img'), 'scan.png', new SubmissionProperties($this->webhooks(), null, null, true));
        $this->copyleaks->submitFileOcr($this->token(), 'ocr1', $submission);
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/ocr/ocr1');
        $this->assertSame('en', $body['langCode']);
        $this->assertSame('scan.png', $body['filename']);
        $this->assertTrue($body['properties']['sandbox']);
    }

    public function testPdfWithoutTitleOrLogo(): void
    {
        $properties = new SubmissionProperties($this->webhooks());
        $properties->pdf = new SubmissionPDF(true, null, null, false, PdfReportVersion::V3);
        $this->copyleaks->submitFile($this->token(), 'pdf1', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/pdf1');
        $this->assertSame(['create' => true, 'rtl' => false, 'version' => 3], $body['properties']['pdf']);
    }

    public function testScanningExcludeWithoutIdPattern(): void
    {
        $properties = new SubmissionProperties($this->webhooks());
        $properties->scanning = new SubmissionScanning(null, new SubmissionScanningExclude(null, null, ['boilerplate']));
        $properties->indexing = new SubmissionIndexing(null, true);
        $this->copyleaks->submitFile($this->token(), 'excl1', new CopyleaksFileSubmissionModel('aGk=', 'a.txt', $properties));
        $body = $this->assertJsonRequest($this->lastRequest(), 'PUT', '/v3/scans/submit/file/excl1');
        $this->assertSame(['exclude' => ['text' => ['boilerplate']]], $body['properties']['scanning']);
        $this->assertSame(['copyleaksDb' => true], $body['properties']['indexing']);
    }
}
