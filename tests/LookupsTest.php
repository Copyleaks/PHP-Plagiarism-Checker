<?php

namespace Copyleaks\Tests;

use Copyleaks\Tests\Support\StubServer;

/**
 * Lookup endpoints: public miscellaneous lists, repository info and correction types.
 */
class LookupsTest extends SdkTestCase
{
    public function testAllowedCrossLanguagesIsPublic(): void
    {
        StubServer::enqueueJson(['documentLanguages' => ['english' => 'en', 'french' => 'fr'],
            'resultLanguages' => ['spanish' => 'es', 'german' => 'de']]);
        $result = $this->copyleaks->getAllowedCrossLanguages();
        $this->assertPublicGet($this->lastRequest(), '/v3/miscellaneous/allowed-cross-languages');
        $this->assertSame('fr', $result->documentLanguages->french);
        $this->assertSame(['es', 'de'], array_values((array) $result->resultLanguages));
    }

    public function testSupportedAiTextDetectionFileTypesIsPublic(): void
    {
        StubServer::enqueueJson(['supportedAiFileTypes' => ['pdf', 'docx', 'LaTeX']]);
        $result = $this->copyleaks->getSupportedAiTextDetectionFileTypes();
        $this->assertPublicGet($this->lastRequest(), '/v3/miscellaneous/supported-ai-text-detection-file-types');
        $this->assertSame(['pdf', 'docx', 'LaTeX'], $result->supportedAiFileTypes);
    }

    public function testRepositoryInfo(): void
    {
        StubServer::enqueueJson(['id' => 'my-repo-123', 'name' => 'Repo', 'permission' => 4, 'status' => 0, 'maxCredits' => 1000,
            'currentCredits' => 900, 'maskingPolicy' => 0, 'creationTime' => '2024-09-09T10:43:52']);
        $result = $this->copyleaks->getRepositoryInfo($this->token(), 'my-repo-123');
        $this->assertAuthGet($this->lastRequest(), '/v3/repositories/repository/my-repo-123/info');
        $this->assertSame('Repo', $result->name);
        $this->assertSame(900, $result->currentCredits);

        $this->copyleaks->getRepositoryInfo($this->token(), 'repo with/slash');
        $this->assertSame('/v3/repositories/repository/repo%20with%2Fslash/info', $this->lastRequest()['path']);
    }

    public function testRepositoryInfoNeedsAnId(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->copyleaks->getRepositoryInfo($this->token(), '');
    }

    public function testSupportedFileTypesUsesTheDocumentedPath(): void
    {
        StubServer::enqueueJson(['textual' => ['txt'], 'ocr' => ['png']]);
        $result = $this->copyleaks->getSupportedFileTypes();
        $this->assertPublicGet($this->lastRequest(), '/v3/miscellaneous/supported-plagiarism-file-types');
        $this->assertSame(['txt'], $result->textual);
    }

    public function testOcrLanguagesSendsTheUserAgent(): void
    {
        StubServer::enqueueJson(['en', 'fr']);
        $this->assertSame(['en', 'fr'], $this->copyleaks->getOCRSupportedLanguages());
        $this->assertPublicGet($this->lastRequest(), '/v3/miscellaneous/ocr-languages-list');
    }

    public function testReleaseNotesSendsTheUserAgent(): void
    {
        StubServer::enqueueJson([['version' => '1']]);
        $this->assertCount(1, $this->copyleaks->getReleaseNotes());
        $this->assertPublicGet($this->lastRequest(), '/v3/release-logs.json');
    }

    public function testCorrectionTypesSendsTheUserAgent(): void
    {
        StubServer::enqueueJson(['corrections' => []]);
        $this->copyleaks->writingAssistantClient->getCorrectionTypes('en');
        $this->assertPublicGet($this->lastRequest(), '/v1/writing-feedback/correction-types/en');
    }
}
