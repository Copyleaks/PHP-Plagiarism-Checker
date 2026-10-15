<?php

namespace Copyleaks\Tests;

use Copyleaks\CopyleaksAiImageDetectionResponseModel;
use Copyleaks\CopyleaksAiImageDetectionScannedDocumentModel;
use Copyleaks\CopyleaksAiVideoDetectionResponseModel;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\CopyleaksImageMetadataModel;
use Copyleaks\CopyleaksImagePlagiarismResponseModel;
use Copyleaks\CopyleaksImagePlagiarismScannedImageModel;
use Copyleaks\CopyleaksMediaMetadataModel;
use Copyleaks\CopyleaksScannedDocumentModel;
use Copyleaks\ExportAiDetection;
use Copyleaks\ExportCrawledVersion;
use Copyleaks\ExportPdfReport;
use Copyleaks\ExportResults;
use Copyleaks\ExportTarget;
use Copyleaks\RequestPayload;
use PHPUnit\Framework\TestCase;

/**
 * Models shared by several products: media metadata, scanned document and export targets.
 */
class SharedModelsTest extends TestCase
{
    private const SCANNED = ['scanId' => 's1', 'actualCredits' => 2, 'expectedCredits' => 3, 'creationTime' => '2026-10-14T10:00:00Z'];
    private const METADATA = ['issuedTime' => '2026-03-17T13:14:57+00:00', 'issuedBy' => 'OpenAI', 'appOrDeviceUsed' => 'Sora', 'contentSummary' => 'AI'];

    private function assertScanned(array $expected, CopyleaksScannedDocumentModel $model): void
    {
        $this->assertSame($expected, [
            'scanId' => $model->scanId, 'actualCredits' => $model->actualCredits,
            'expectedCredits' => $model->expectedCredits, 'creationTime' => $model->creationTime,
        ]);
    }

    public function testImageVideoAndImagePlagiarismShareTheScannedDocumentModel(): void
    {
        $image = CopyleaksAiImageDetectionResponseModel::fromArray(['model' => 'm', 'scannedDocument' => self::SCANNED])->scannedDocument;
        $this->assertInstanceOf(CopyleaksAiImageDetectionScannedDocumentModel::class, $image);
        $this->assertScanned(self::SCANNED, $image);

        $video = CopyleaksAiVideoDetectionResponseModel::fromArray(['scannedVideo' => self::SCANNED])->scannedVideo;
        $this->assertSame(CopyleaksScannedDocumentModel::class, get_class($video));
        $this->assertScanned(self::SCANNED, $video);

        $plagiarism = CopyleaksImagePlagiarismResponseModel::fromArray(['scannedImage' => self::SCANNED + ['width' => 640, 'filename' => 'a.png']])->scannedImage;
        $this->assertInstanceOf(CopyleaksImagePlagiarismScannedImageModel::class, $plagiarism);
        $this->assertScanned(self::SCANNED, $plagiarism);
        $this->assertSame(640, $plagiarism->width);
        $this->assertNull($plagiarism->height);
        $this->assertSame('a.png', $plagiarism->filename);
    }

    public function testScannedDocumentDefaultsAsInEarlierReleases(): void
    {
        $this->assertScanned(['scanId' => '', 'actualCredits' => 0, 'expectedCredits' => 0, 'creationTime' => ''],
            CopyleaksAiImageDetectionScannedDocumentModel::fromArray([]));
        $this->assertScanned(['scanId' => '7', 'actualCredits' => 2, 'expectedCredits' => 0, 'creationTime' => ''],
            CopyleaksScannedDocumentModel::fromArray(['scanId' => 7, 'actualCredits' => '2', 'expectedCredits' => [1], 'creationTime' => ['x']]));
        $this->assertNull(CopyleaksAiImageDetectionScannedDocumentModel::fromArray(null));

        $built = new CopyleaksAiImageDetectionScannedDocumentModel('s', 1, 2, 'c');
        $this->assertSame(['scanId' => 's', 'actualCredits' => 1, 'expectedCredits' => 2, 'creationTime' => 'c'], get_object_vars($built));
        $this->assertSame(4, (new \ReflectionMethod(CopyleaksAiImageDetectionScannedDocumentModel::class, '__construct'))->getNumberOfRequiredParameters());
    }

    public function testImageAndVideoShareTheMetadataModel(): void
    {
        $image = CopyleaksAiImageDetectionResponseModel::fromArray(['model' => 'm', 'imageInfo' => ['metadata' => self::METADATA]])->imageInfo->metadata;
        $this->assertInstanceOf(CopyleaksImageMetadataModel::class, $image);
        $this->assertSame(self::METADATA, get_object_vars($image));

        $video = CopyleaksAiVideoDetectionResponseModel::fromArray(['videoInfo' => ['metadata' => self::METADATA]])->videoInfo->metadata;
        $this->assertSame(CopyleaksMediaMetadataModel::class, get_class($video));
        $this->assertSame(self::METADATA, get_object_vars($video));
    }

    public function testImageMetadataKeepsItsEarlierBehavior(): void
    {
        $earlier = new CopyleaksImageMetadataModel('t', 'b', 'a');
        $this->assertSame(['issuedTime' => 't', 'issuedBy' => 'b', 'appOrDeviceUsed' => 'a', 'contentSummary' => null], get_object_vars($earlier));
        $this->assertSame(['issuedTime' => '12', 'issuedBy' => '1.5', 'appOrDeviceUsed' => '1', 'contentSummary' => null],
            get_object_vars(CopyleaksImageMetadataModel::fromArray(['issuedTime' => 12, 'issuedBy' => 1.5, 'appOrDeviceUsed' => true])));
        $this->assertNull(CopyleaksMediaMetadataModel::fromArray(['issuedBy' => ['not', 'a', 'string']])->issuedBy);
        $this->assertNull(CopyleaksImageMetadataModel::fromArray(null));
    }

    /**
     * fromArray() of the classes from earlier releases still declares their own class as its return type.
     */
    public function testEarlierClassesKeepTheirFromArrayReturnType(): void
    {
        foreach ([CopyleaksImageMetadataModel::class, CopyleaksAiImageDetectionScannedDocumentModel::class] as $class) {
            $type = (new \ReflectionMethod($class, 'fromArray'))->getReturnType();
            $this->assertTrue($type->allowsNull(), $class);
            $this->assertContains($type->getName(), ['self', $class], $class);
            $this->assertSame($class, (new \ReflectionMethod($class, 'fromArray'))->getDeclaringClass()->getName());
        }
    }

    public function testExportTargetsShareExportTarget(): void
    {
        foreach ([new ExportPdfReport('https://e/p', 'POST'), new ExportCrawledVersion('https://e/c', 'PUT'),
            new ExportResults('r1', 'https://e/r', 'POST'), new ExportAiDetection('https://e/a', 'POST')] as $target) {
            $this->assertInstanceOf(ExportTarget::class, $target);
        }
    }

    /**
     * The JSON is byte for byte what earlier releases produced, with `id` first.
     */
    public function testExportTargetsSerializeAsBefore(): void
    {
        $results = new ExportResults('r1', 'https://e/r', 'POST', [['Authorization', 'Bearer x']]);
        $this->assertSame('{"id":"r1","endpoint":"https:\/\/e\/r","verb":"POST","headers":[["Authorization","Bearer x"]]}', json_encode($results));
        $this->assertSame('{"id":"r1","endpoint":"https:\/\/e\/r","verb":"POST","headers":null}', json_encode(new ExportResults('r1', 'https://e/r', 'POST')));
        $this->assertSame('{"endpoint":"https:\/\/e\/p","verb":"POST","headers":null}', json_encode(new ExportPdfReport('https://e/p', 'POST')));
        $this->assertSame('{"endpoint":"https:\/\/e\/c","verb":"PUT","headers":[["a","b"]]}', json_encode(new ExportCrawledVersion('https://e/c', 'PUT', [['a', 'b']])));
        $this->assertSame('{"id":"r1","endpoint":"https://e/r","verb":"POST"}', RequestPayload::encode(new ExportResults('r1', 'https://e/r', 'POST')));

        $model = new CopyleaksExportModel('https://e/done', [$results], new ExportCrawledVersion('https://e/c', 'POST'), new ExportPdfReport('https://e/p', 'POST'), 3, 'p');
        $this->assertSame(
            '{"completionWebhook":"https://e/done","results":[{"id":"r1","endpoint":"https://e/r","verb":"POST","headers":[["Authorization","Bearer x"]]}],'
            . '"crawledVersion":{"endpoint":"https://e/c","verb":"POST"},"maxRetries":3,"developerPayload":"p","pdfReport":{"endpoint":"https://e/p","verb":"POST"}}',
            RequestPayload::encode($model)
        );
    }
}
