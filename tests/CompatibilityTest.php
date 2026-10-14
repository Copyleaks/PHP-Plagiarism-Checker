<?php

namespace Copyleaks\Tests;

use Copyleaks\AIGeneratedText;
use Copyleaks\AuthExipredException;
use Copyleaks\CommandException;
use Copyleaks\CopyleaksAiImageDetectionModels;
use Copyleaks\CopyleaksAiImageDetectionRequestModel;
use Copyleaks\CopyleaksDeleteRequestModel;
use Copyleaks\CopyleaksExportModel;
use Copyleaks\CopyleaksNaturalLanguageSubmissionModel;
use Copyleaks\CopyleaksTextModerationLabel;
use Copyleaks\CopyleaksTextModerationRequestModel;
use Copyleaks\ExportCrawledVersion;
use Copyleaks\ExportPdfReport;
use Copyleaks\IdObject;
use Copyleaks\RateLimitException;
use Copyleaks\ReportCustomizationColors;
use Copyleaks\SubmissionFilter;
use Copyleaks\SubmissionIndexing;
use Copyleaks\SubmissionPDF;
use Copyleaks\SubmissionRepository;
use Copyleaks\SubmissionScanning;
use Copyleaks\SubmissionScanningCopyleaksDB;
use Copyleaks\SubmissionScanningExclude;
use Copyleaks\SubmissionWebhooks;
use Copyleaks\UnderMaintenanceException;
use PHPUnit\Framework\TestCase;

/**
 * Backward compatibility with the earlier public API, and a clean load.
 */
class CompatibilityTest extends TestCase
{
    /**
     * Every class, public method, parameter, property and constant of the earlier release still exists.
     * New parameters are only added at the end, and no parameter became required.
     */
    public function testPublicApiOfTheEarlierReleaseIsKept(): void
    {
        $before = json_decode(file_get_contents(__DIR__ . '/fixtures/public-api-before-5.3.json'), true);
        $this->assertGreaterThan(90, count($before));

        foreach ($before as $class => $entry) {
            $this->assertTrue(class_exists($class), "class $class");
            $reflection = new \ReflectionClass($class);
            foreach ($entry['methods'] as $method => $old) {
                $this->assertTrue($reflection->hasMethod($method), "$class::$method");
                $now = $reflection->getMethod($method);
                $this->assertTrue($now->isPublic(), "$class::$method is public");
                $this->assertSame($old['static'], $now->isStatic(), "$class::$method static");
                $names = array_map(function ($p) {
                    return $p->getName();
                }, $now->getParameters());
                $this->assertSame($old['params'], array_slice($names, 0, count($old['params'])), "$class::$method parameters");
                $this->assertLessThanOrEqual($old['required'], $now->getNumberOfRequiredParameters(), "$class::$method required parameters");
            }
            foreach ($entry['properties'] as $property) {
                $this->assertTrue($reflection->hasProperty($property) && $reflection->getProperty($property)->isPublic(), "$class::\$$property");
            }
            foreach ($entry['constants'] as $constant) {
                $this->assertTrue($reflection->hasConstant($constant), "$class::$constant");
            }
        }
    }

    public function testEarlierConstructorCallsStillWork(): void
    {
        $filter = new SubmissionFilter(true, true, true);
        $this->assertTrue($filter->minorChangesEnabled);
        $scanning = new SubmissionScanning(true, new SubmissionScanningExclude('php-test-*'), null, new SubmissionScanningCopyleaksDB(true, true));
        $this->assertTrue($scanning->internet);
        $this->assertSame('php-test-*', $scanning->exclude->idPattern);
        $this->assertSame('repoId', (new SubmissionIndexing([new SubmissionRepository('repoId')]))->repositories[0]->id);
        $pdf = new SubmissionPDF(true, 'title', 'logo', false, 2, new ReportCustomizationColors('#1', '#2', '#3', '#4', '#5'), 'v2');
        $this->assertSame('#5', $pdf->colors->relatedMeaning);
        $this->assertSame('n', (new SubmissionWebhooks('s', 'n'))->newResult);
        $this->assertTrue((new AIGeneratedText(true))->detect);

        $export = new CopyleaksExportModel('https://e/done', [], new ExportCrawledVersion('https://e/c', 'POST'), new ExportPdfReport('https://e/p', 'POST'), 3, 'p');
        $this->assertSame(3, $export->maxRetries);
        $delete = new CopyleaksDeleteRequestModel([new IdObject('a')], true, 'https://e/deleted');
        $this->assertSame('https://e/deleted', $delete->completionWebhook);

        $moderation = new CopyleaksTextModerationRequestModel('text', true, 'en', [new CopyleaksTextModerationLabel('toxic-v1')]);
        $this->assertSame('en', $moderation->language);
        $this->assertSame('en', (new CopyleaksNaturalLanguageSubmissionModel('t', true, 'en'))->language);
        $image = new CopyleaksAiImageDetectionRequestModel('aW1n', 'a.png', CopyleaksAiImageDetectionModels::AI_IMAGE_1_ULTRA, true);
        $this->assertSame([], $image->validate());
    }

    public function testExceptionsKeepTheirEarlierConstructors(): void
    {
        $this->assertSame('message', (new CommandException('message'))->getMessage());
        $this->assertSame(0, (new CommandException('message'))->getStatusCode());
        $this->assertSame(0, (new RateLimitException())->getCode());
        $this->assertNull((new RateLimitException())->getResponseBody());
        $this->assertSame(0, (new UnderMaintenanceException())->getCode());
        $this->assertStringContainsString('Authentication Expired', (new AuthExipredException())->getMessage());
    }

    /**
     * Every class shipped in src/ is loaded by the package autoloader.
     */
    public function testEveryShippedClassIsLoaded(): void
    {
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator(dirname(__DIR__) . '/src'));
        $count = 0;
        foreach ($files as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }
            preg_match_all('/^\s*(?:final\s+|abstract\s+)?(class|trait|interface)\s+(\w+)/m', file_get_contents($file->getPathname()), $matches, PREG_SET_ORDER);
            foreach ($matches as $match) {
                $name = 'Copyleaks\\' . $match[2];
                $exists = $match[1] === 'trait' ? trait_exists($name) : ($match[1] === 'interface' ? interface_exists($name) : class_exists($name));
                $this->assertTrue($exists, "$name (" . $file->getFilename() . ') is not loaded');
                $count++;
            }
        }
        $this->assertGreaterThan(150, $count);
    }

    /**
     * Loading the SDK with every error reported prints nothing: no deprecation notices, no output.
     */
    public function testLoadsWithoutNoticesOrOutput(): void
    {
        $code = 'require ' . var_export(dirname(__DIR__) . '/autoload.php', true) . '; new \Copyleaks\Copyleaks(); echo "loaded";';
        $command = escapeshellarg(PHP_BINARY) . ' -d error_reporting=-1 -d display_errors=1 -d display_startup_errors=1 -r ' . escapeshellarg($code) . ' 2>&1';
        $output = shell_exec('cd ' . escapeshellarg(sys_get_temp_dir()) . ' && ' . $command);
        $this->assertSame('loaded', $output);
    }
}
