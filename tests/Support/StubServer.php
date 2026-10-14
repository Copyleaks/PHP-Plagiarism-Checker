<?php

namespace Copyleaks\Tests\Support;

/**
 * Starts tests/Support/stub-server.php once per test run and gives access to the requests it received.
 */
final class StubServer
{
    /** @var resource|null */
    private static $process = null;
    private static string $dir = '';
    private static int $port = 0;

    public static function start(): void
    {
        if (self::$process !== null) {
            return;
        }
        self::$dir = sys_get_temp_dir() . '/copyleaks-php-stub-' . getmypid() . '-' . bin2hex(random_bytes(4));
        mkdir(self::$dir, 0700, true);
        file_put_contents(self::$dir . '/responses.json', '[]');
        touch(self::$dir . '/requests.jsonl');

        $process = proc_open(
            [PHP_BINARY, '-d', 'display_errors=stderr', __DIR__ . '/stub-server.php', self::$dir],
            [0 => ['pipe', 'r'], 1 => ['file', self::$dir . '/server.out', 'a'], 2 => ['file', self::$dir . '/server.err', 'a']],
            $pipes
        );
        if (!is_resource($process)) {
            throw new \RuntimeException('Cannot start the stub server.');
        }
        self::$process = $process;

        $deadline = microtime(true) + 10;
        while (!is_file(self::$dir . '/port')) {
            if (microtime(true) > $deadline) {
                throw new \RuntimeException('The stub server did not start: ' . @file_get_contents(self::$dir . '/server.err'));
            }
            usleep(20000);
        }
        self::$port = (int) file_get_contents(self::$dir . '/port');
        register_shutdown_function([self::class, 'stop']);
    }

    public static function stop(): void
    {
        if (self::$process !== null) {
            proc_terminate(self::$process);
            proc_close(self::$process);
            self::$process = null;
        }
        if (self::$dir !== '' && is_dir(self::$dir)) {
            foreach (glob(self::$dir . '/*') ?: [] as $file) {
                @unlink($file);
            }
            @rmdir(self::$dir);
        }
    }

    public static function baseUrl(): string
    {
        return 'http://127.0.0.1:' . self::$port;
    }

    public static function reset(): void
    {
        file_put_contents(self::$dir . '/requests.jsonl', '', LOCK_EX);
        file_put_contents(self::$dir . '/responses.json', '[]', LOCK_EX);
    }

    /**
     * Queues responses, answered in order. Each is ['status' => int, 'headers' => [], 'body' => string,
     * 'delay' => seconds, 'close' => bool, 'gzip' => bool].
     */
    public static function enqueue(array ...$responses): void
    {
        $file = self::$dir . '/responses.json';
        $queue = json_decode((string) file_get_contents($file), true) ?: [];
        foreach ($responses as $response) {
            $queue[] = $response + ['status' => 200, 'headers' => ['Content-Type' => 'application/json; charset=utf-8'], 'body' => '{}'];
        }
        file_put_contents($file, json_encode($queue), LOCK_EX);
    }

    /**
     * Queues a JSON response.
     * @param mixed $body
     */
    public static function enqueueJson($body, int $status = 200, array $headers = []): void
    {
        self::enqueue([
            'status' => $status,
            'headers' => $headers + ['Content-Type' => 'application/json; charset=utf-8'],
            'body' => is_string($body) ? $body : json_encode($body),
        ]);
    }

    /**
     * @return array[] The recorded requests, oldest first. 'body' is decoded to the raw bytes.
     */
    public static function requests(): array
    {
        $requests = [];
        foreach (file(self::$dir . '/requests.jsonl', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
            $request = json_decode($line, true);
            $request['body'] = base64_decode($request['body']);
            $requests[] = $request;
        }
        return $requests;
    }

    public static function lastRequest(): array
    {
        $requests = self::requests();
        if ($requests === []) {
            throw new \RuntimeException('The stub server received no request.');
        }
        return end($requests);
    }
}
