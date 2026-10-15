<?php
/*
 * Local HTTP/1.1 stub server for the SDK tests. Usage: php stub-server.php <state-dir>
 * Listens on a free 127.0.0.1 port (written to <state-dir>/port) and records each request as one JSON line in
 * <state-dir>/requests.jsonl: connection number, method, target, path, query, lowercase headers, raw header lines
 * and the base64 body. Answers with the first response queued in <state-dir>/responses.json ({status, headers,
 * body, delay, close, gzip, truncate}), or 200 {} when empty; truncate sends the full Content-Length but half the
 * body, then closes. Supports keep-alive, concurrent connections, "Expect: 100-continue" and chunked bodies.
 */

$dir = $argv[1] ?? '';
if ($dir === '' || !is_dir($dir)) {
    fwrite(STDERR, "usage: php stub-server.php <state-dir>\n");
    exit(2);
}

$server = stream_socket_server('tcp://127.0.0.1:0', $errno, $errstr);
if ($server === false) {
    fwrite(STDERR, "cannot listen: $errstr\n");
    exit(1);
}
stream_set_blocking($server, false);
$name = stream_socket_get_name($server, false);
file_put_contents("$dir/port.tmp", substr($name, strrpos($name, ':') + 1));
rename("$dir/port.tmp", "$dir/port");

$clients = [];
$connections = 0;

while (true) {
    $read = [$server];
    $timeout = 200000;
    $now = microtime(true);
    foreach ($clients as $client) {
        if ($client['pending'] === null) {
            $read[] = $client['sock'];
        } else {
            $timeout = min($timeout, max(0, (int) (($client['pending']['at'] - $now) * 1e6)));
        }
    }
    $write = null;
    $except = null;
    if (@stream_select($read, $write, $except, 0, $timeout) === false) {
        usleep(10000);
        continue;
    }
    foreach ($read as $sock) {
        if ($sock === $server) {
            $accepted = @stream_socket_accept($server, 0);
            if ($accepted !== false) {
                stream_set_blocking($accepted, false);
                $connections++;
                $clients[(int) $accepted] = ['sock' => $accepted, 'buf' => '', 'conn' => $connections, 'continued' => false, 'pending' => null];
            }
            continue;
        }
        $id = (int) $sock;
        $data = @fread($sock, 1 << 20);
        if ($data === '' || $data === false) {
            if (feof($sock)) {
                @fclose($sock);
                unset($clients[$id]);
            }
            continue;
        }
        $clients[$id]['buf'] .= $data;
        handle($clients, $id, $dir);
    }
    $now = microtime(true);
    foreach (array_keys($clients) as $id) {
        if (isset($clients[$id]) && $clients[$id]['pending'] !== null && $clients[$id]['pending']['at'] <= $now) {
            $response = $clients[$id]['pending']['response'];
            $clients[$id]['pending'] = null;
            respond($clients, $id, $response);
            if (isset($clients[$id])) {
                handle($clients, $id, $dir);
            }
        }
    }
}

function handle(array &$clients, int $id, string $dir): void
{
    while (isset($clients[$id]) && $clients[$id]['pending'] === null) {
        $buf = $clients[$id]['buf'];
        $headerEnd = strpos($buf, "\r\n\r\n");
        if ($headerEnd === false) {
            return;
        }
        $lines = explode("\r\n", substr($buf, 0, $headerEnd));
        $requestLine = array_shift($lines);
        $parts = explode(' ', $requestLine);
        $method = $parts[0] ?? '';
        $target = $parts[1] ?? '';
        $headers = [];
        foreach ($lines as $line) {
            $pair = explode(':', $line, 2);
            $headers[strtolower(trim($pair[0]))] = trim($pair[1] ?? '');
        }
        $rest = substr($buf, $headerEnd + 4);
        $expectsContinue = stripos($headers['expect'] ?? '', '100-continue') !== false;

        $body = null;
        $consumed = 0;
        if (stripos($headers['transfer-encoding'] ?? '', 'chunked') !== false) {
            $decoded = '';
            $pos = 0;
            while (true) {
                $lineEnd = strpos($rest, "\r\n", $pos);
                if ($lineEnd === false) {
                    break;
                }
                $size = hexdec(trim(explode(';', substr($rest, $pos, $lineEnd - $pos))[0]));
                if ($size == 0) {
                    if (strlen($rest) >= $lineEnd + 4) {
                        $body = $decoded;
                        $consumed = $lineEnd + 4;
                    }
                    break;
                }
                $chunkStart = $lineEnd + 2;
                if (strlen($rest) < $chunkStart + $size + 2) {
                    break;
                }
                $decoded .= substr($rest, $chunkStart, (int) $size);
                $pos = $chunkStart + (int) $size + 2;
            }
        } else {
            $length = (int) ($headers['content-length'] ?? 0);
            if (strlen($rest) >= $length) {
                $body = substr($rest, 0, $length);
                $consumed = $length;
            }
        }

        if ($body === null) {
            if ($expectsContinue && !$clients[$id]['continued']) {
                @fwrite($clients[$id]['sock'], "HTTP/1.1 100 Continue\r\n\r\n");
                $clients[$id]['continued'] = true;
            }
            return;
        }

        $clients[$id]['buf'] = substr($rest, $consumed);
        $clients[$id]['continued'] = false;

        $url = parse_url($target);
        $record = [
            'conn' => $clients[$id]['conn'],
            'method' => $method,
            'target' => $target,
            'path' => $url['path'] ?? '',
            'query' => $url['query'] ?? '',
            'headers' => $headers,
            'rawHeaders' => $lines,
            'body' => base64_encode($body),
        ];
        file_put_contents("$dir/requests.jsonl", json_encode($record) . "\n", FILE_APPEND | LOCK_EX);

        $response = nextResponse($dir);
        if (($response['delay'] ?? 0) > 0) {
            $clients[$id]['pending'] = ['at' => microtime(true) + $response['delay'], 'response' => $response];
            return;
        }
        respond($clients, $id, $response);
    }
}

function nextResponse(string $dir): array
{
    $file = "$dir/responses.json";
    $fp = fopen($file, 'c+');
    flock($fp, LOCK_EX);
    $queue = json_decode(stream_get_contents($fp) ?: '[]', true) ?: [];
    $response = array_shift($queue);
    ftruncate($fp, 0);
    rewind($fp);
    fwrite($fp, json_encode($queue));
    flock($fp, LOCK_UN);
    fclose($fp);
    return $response ?? ['status' => 200, 'headers' => ['Content-Type' => 'application/json; charset=utf-8'], 'body' => '{}'];
}

function respond(array &$clients, int $id, array $response): void
{
    $status = (int) ($response['status'] ?? 200);
    $body = (string) ($response['body'] ?? '');
    $headers = $response['headers'] ?? ['Content-Type' => 'application/json; charset=utf-8'];
    if (!empty($response['gzip'])) {
        $body = gzencode($body);
        $headers['Content-Encoding'] = 'gzip';
    }
    $close = !empty($response['close']) || !empty($response['truncate']);
    $reasons = [200 => 'OK', 201 => 'Created', 202 => 'Accepted', 204 => 'No Content', 400 => 'Bad Request',
        401 => 'Unauthorized', 404 => 'Not Found', 409 => 'Conflict', 429 => 'Too Many Requests',
        500 => 'Internal Server Error', 503 => 'Service Unavailable'];
    $out = "HTTP/1.1 $status " . ($reasons[$status] ?? 'Status') . "\r\n";
    foreach ($headers as $name => $value) {
        $out .= "$name: $value\r\n";
    }
    $out .= 'Content-Length: ' . strlen($body) . "\r\n";
    $out .= $close ? "Connection: close\r\n" : "Connection: keep-alive\r\n";
    $out .= "\r\n" . (empty($response['truncate']) ? $body : substr($body, 0, intdiv(strlen($body), 2)));

    $sock = $clients[$id]['sock'];
    stream_set_blocking($sock, true);
    $written = @fwrite($sock, $out);
    stream_set_blocking($sock, false);
    if ($written === false || $close) {
        @fclose($sock);
        unset($clients[$id]);
    }
}
