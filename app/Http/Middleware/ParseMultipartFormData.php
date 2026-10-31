<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\Response;

class ParseMultipartFormData
{
    /**
     * Handle an incoming request.
     *
     * In PHP, native PUT and PATCH requests do not automatically populate $_POST and $_FILES
     * when Content-Type is multipart/form-data. This middleware parses the multipart body
     * and injects the parameters and files into the Laravel Request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (in_array($request->method(), ['PUT', 'PATCH'], true)) {
            $contentType = (string) ($request->header('CONTENT_TYPE') ?? $request->header('content-type') ?? '');
            if (str_contains($contentType, 'multipart/form-data')) {
                $this->parseMultipart($request, $contentType);
            }
        }

        return $next($request);
    }

    protected function parseMultipart(Request $request, string $contentType): void
    {
        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        if (!preg_match('/boundary=(.*)$/', $contentType, $matches)) {
            return;
        }

        $boundary = trim($matches[1], '"');
        $parts = explode('--' . $boundary, $content);

        $parameters = [];
        $files = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if (empty($part) || $part === '--') {
                continue;
            }

            if (strpos($part, "\r\n\r\n") !== false) {
                [$rawHeaders, $body] = explode("\r\n\r\n", $part, 2);
            } elseif (strpos($part, "\n\n") !== false) {
                [$rawHeaders, $body] = explode("\n\n", $part, 2);
            } else {
                continue;
            }

            if (preg_match('/name="([^"]+)"/', $rawHeaders, $nameMatch)) {
                $name = $nameMatch[1];

                if (preg_match('/filename="([^"]*)"/', $rawHeaders, $fileMatch)) {
                    $filename = $fileMatch[1];
                    if (!empty($filename)) {
                        $tmpPath = tempnam(sys_get_temp_dir(), 'laravel_multipart_');
                        file_put_contents($tmpPath, $body);

                        $mime = 'application/octet-stream';
                        if (preg_match('/Content-Type: ([^\r\n]+)/', $rawHeaders, $mimeMatch)) {
                            $mime = trim($mimeMatch[1]);
                        }

                        $file = new UploadedFile(
                            $tmpPath,
                            $filename,
                            $mime,
                            null,
                            true
                        );

                        $this->setNestedData($files, $name, $file);
                    }
                } else {
                    $this->setNestedData($parameters, $name, $body);
                }
            }
        }

        $request->request->add($parameters);
        $request->files->add($files);
    }

    /**
     * Set parameter or file into target array, supporting array bracket notation (e.g. field[key]).
     *
     * @param array<string, mixed> $target
     */
    protected function setNestedData(array &$target, string $name, mixed $value): void
    {
        if (str_contains($name, '[')) {
            parse_str($name . '=' . urlencode(is_string($value) ? $value : ''), $parsed);
            $target = array_replace_recursive($target, $parsed);
        } else {
            $target[$name] = $value;
        }
    }
}

