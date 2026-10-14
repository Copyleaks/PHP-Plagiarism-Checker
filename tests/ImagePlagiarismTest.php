<?php

namespace Copyleaks\Tests;

use Copyleaks\CopyleaksImagePlagiarismMatchTypes;
use Copyleaks\CopyleaksImagePlagiarismRequestModel;
use Copyleaks\CopyleaksImagePlagiarismResponseModel;
use Copyleaks\Tests\Support\StubServer;

/**
 * Image Plagiarism Detector: multipart upload.
 */
class ImagePlagiarismTest extends SdkTestCase
{
    private const RESPONSE = '{
      "developerPayload": "my-custom-data",
      "scannedImage": {"scanId": "my-scan-1", "expectedCredits": 1, "actualCredits": 1, "creationTime": "2026-05-24T10:00:00Z",
        "width": 1920, "height": 1080, "filename": "my-photo.jpg"},
      "matches": {
        "internet": [
          {"url": "https://example.com/images/photo.jpg", "matchType": 0,
           "webPages": [{"url": "https://example.com/blog/my-post"}, {"url": "https://example.org/news/article"}]},
          {"url": "https://example.org/gallery/photo-sm.jpg", "matchType": 1}
        ],
        "score": {"totalMatches": 2, "fullMatches": 1, "partialMatches": 1}
      }
    }';

    private string $imagePath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $base = tempnam(sys_get_temp_dir(), 'climg');
        @unlink($base);
        $this->imagePath = $base . '.png';
        file_put_contents($this->imagePath, "\x89PNG\r\n\x1a\n" . random_bytes(3000) . "\0\r\n--edge");
    }

    protected function tearDown(): void
    {
        @unlink($this->imagePath);
        parent::tearDown();
    }

    /**
     * Splits a multipart/form-data body into its parts: name => ['headers' => [...], 'body' => string].
     */
    private function parseMultipart(array $request): array
    {
        $this->assertMatchesRegularExpression('#^multipart/form-data; boundary=(\S+)$#', $request['headers']['content-type']);
        preg_match('#boundary=(\S+)$#', $request['headers']['content-type'], $m);
        $boundary = $m[1];
        $body = $request['body'];
        $this->assertStringEndsWith("--$boundary--\r\n", $body);

        $parts = [];
        $chunks = explode("--$boundary", $body);
        array_shift($chunks); // before the first boundary
        array_pop($chunks);   // the closing "--\r\n"
        foreach ($chunks as $chunk) {
            $chunk = substr($chunk, 2, -2); // strip the CRLF after the boundary and before the next one
            [$head, $content] = explode("\r\n\r\n", $chunk, 2);
            $headers = [];
            foreach (explode("\r\n", $head) as $line) {
                [$name, $value] = explode(':', $line, 2);
                $headers[strtolower($name)] = trim($value);
            }
            preg_match('#name="([^"]+)"#', $headers['content-disposition'], $n);
            $parts[$n[1]] = ['headers' => $headers, 'body' => $content];
        }
        return $parts;
    }

    public function testUploadsTheImageFileAsMultipart(): void
    {
        StubServer::enqueueJson(self::RESPONSE);
        $submission = new CopyleaksImagePlagiarismRequestModel($this->imagePath, 'my-photo.png', true, 'my-custom-data');
        $result = $this->copyleaks->imagePlagiarismClient->submit($this->token(), 'img1', $submission);

        $request = $this->lastRequest();
        $this->assertRequest($request, 'POST', '/v1/image-plagiarism-detector/img1/check');
        $this->assertSame('Bearer ' . self::TOKEN, $request['headers']['authorization']);
        $this->assertSame((string) strlen($request['body']), $request['headers']['content-length']);

        $parts = $this->parseMultipart($request);
        $this->assertSame(['image', 'filename', 'sandbox', 'developerPayload'], array_keys($parts));
        $this->assertSame('form-data; name="image"; filename="my-photo.png"', $parts['image']['headers']['content-disposition']);
        $this->assertSame('image/png', $parts['image']['headers']['content-type']);
        $this->assertSame(file_get_contents($this->imagePath), $parts['image']['body']);
        $this->assertSame('my-photo.png', $parts['filename']['body']);
        $this->assertSame('true', $parts['sandbox']['body']);
        $this->assertSame('my-custom-data', $parts['developerPayload']['body']);

        $this->assertSame(2, $result->matches->score->totalMatches);
    }

    public function testUploadsImageBytesFromMemory(): void
    {
        $bytes = file_get_contents($this->imagePath);
        $this->copyleaks->imagePlagiarismClient->submit($this->token(), 'img2', CopyleaksImagePlagiarismRequestModel::fromContent($bytes, 'photo.jpg', false));

        $parts = $this->parseMultipart($this->lastRequest());
        $this->assertSame(['image', 'filename', 'sandbox'], array_keys($parts));
        $this->assertSame('form-data; name="image"; filename="photo.jpg"', $parts['image']['headers']['content-disposition']);
        $this->assertSame('image/jpeg', $parts['image']['headers']['content-type']);
        $this->assertSame($bytes, $parts['image']['body']);
        $this->assertSame('false', $parts['sandbox']['body']);
    }

    public function testOptionalFieldsAreLeftOut(): void
    {
        $this->copyleaks->imagePlagiarismClient->submit($this->token(), 'img3', new CopyleaksImagePlagiarismRequestModel($this->imagePath, 'p.webp'));
        $parts = $this->parseMultipart($this->lastRequest());
        $this->assertSame(['image', 'filename'], array_keys($parts));
        $this->assertSame('image/webp', $parts['image']['headers']['content-type']);
    }

    public function testMissingImageFileIsRejectedBeforeSending(): void
    {
        try {
            $this->copyleaks->imagePlagiarismClient->submit($this->token(), 'img4', new CopyleaksImagePlagiarismRequestModel('/no/such/file.png', 'file.png', true));
            $this->fail('expected InvalidArgumentException');
        } catch (\InvalidArgumentException $e) {
            $this->assertStringContainsString('/no/such/file.png', $e->getMessage());
            $this->assertSame([], StubServer::requests());
        }
    }

    public function testResponseModel(): void
    {
        $model = CopyleaksImagePlagiarismResponseModel::fromArray(json_decode(self::RESPONSE, true));
        $this->assertSame('my-custom-data', $model->developerPayload);
        $this->assertSame('my-scan-1', $model->scannedImage->scanId);
        $this->assertSame(1920, $model->scannedImage->width);
        $this->assertSame(1080, $model->scannedImage->height);
        $this->assertSame('my-photo.jpg', $model->scannedImage->filename);
        $this->assertSame(1, $model->scannedImage->actualCredits);
        $this->assertCount(2, $model->matches->internet);
        $this->assertSame(CopyleaksImagePlagiarismMatchTypes::FULL, $model->matches->internet[0]->matchType);
        $this->assertSame('https://example.org/news/article', $model->matches->internet[0]->webPages[1]->url);
        $this->assertSame(CopyleaksImagePlagiarismMatchTypes::PARTIAL, $model->matches->internet[1]->matchType);
        $this->assertSame([], $model->matches->internet[1]->webPages);
        $this->assertSame(1, $model->matches->score->fullMatches);
        $this->assertSame(1, $model->matches->score->partialMatches);
        $this->assertNull(CopyleaksImagePlagiarismResponseModel::fromArray(['developerPayload' => null])->matches);
    }
}
