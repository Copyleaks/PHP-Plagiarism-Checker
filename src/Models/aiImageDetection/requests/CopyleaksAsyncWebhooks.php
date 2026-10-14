<?php
/*
 The MIT License(MIT)

 Copyright(c) 2016 Copyleaks LTD (https://copyleaks.com)

 Permission is hereby granted, free of charge, to any person obtaining a copy
 of this software and associated documentation files (the "Software"), to deal
 in the Software without restriction, including without limitation the rights
 to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 copies of the Software, and to permit persons to whom the Software is
 furnished to do so, subject to the following conditions:

 The above copyright notice and this permission notice shall be included in all
 copies or substantial portions of the Software.

 THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 SOFTWARE.
*/

namespace Copyleaks;

/**
 * Webhook settings of an asynchronous detection (AI Image Detection async, AI Video Detection).
 */
class CopyleaksAsyncWebhooks
{
    /**
     * The URL that Copyleaks sends the detection result to (HTTP POST) when processing is complete.
     */
    public string $url;

    /**
     * Headers to add to the webhook request, as [["Header-Name", "Header-Value"], ...].
     * Leave null for none.
     * @var string[][]|null
     */
    public ?array $headers;

    /**
     * @param string $url The URL that Copyleaks sends the detection result to when processing is complete.
     * @param string[][]|null $headers Headers to add to the webhook request, as [["Header-Name", "Header-Value"], ...].
     */
    public function __construct(string $url, ?array $headers = null)
    {
        $this->url = $url;
        $this->headers = $headers;
    }
}
