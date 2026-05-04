<?php

namespace App\Services;

use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;

class LinkMetadataService
{
    public function fetch(string $url): array
    {
        return $this->youtubeOembed($url)
            ?? $this->redditMetadata($url)
            ?? $this->openGraphMetadata($url)
            ?? [];
    }

    public function merge(array $validated, array $metadata): array
    {
        foreach ($metadata as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            if ($key === 'metadata') {
                $validated['metadata'] = array_merge(
                    $validated['metadata'] ?? [],
                    $value
                );

                continue;
            }

            if (empty($validated[$key])) {
                $validated[$key] = $value;
            }
        }

        return $validated;
    }

    private function youtubeOembed(string $url): ?array
    {
        $host = parse_url($url, PHP_URL_HOST);

        $isYoutube = $host && (
            str_contains($host, 'youtube.com') ||
            str_contains($host, 'youtu.be')
        );

        if (! $isYoutube) {
            return null;
        }

        try {
            $response = Http::timeout(10)->get('https://www.youtube.com/oembed', [
                'url' => $url,
                'format' => 'json',
            ]);

            if (! $response->successful()) {
                return null;
            }

            $data = $response->json();

            return [
                'type' => 'video',
                'title' => $data['title'] ?? null,
                'description' => null,
                'image_url' => $data['thumbnail_url'] ?? null,
                'site_name' => 'YouTube',
                'provider_name' => $data['provider_name'] ?? 'YouTube',
                'final_url' => $url,
                'metadata' => [
                    'provider' => 'youtube',
                    'author_name' => $data['author_name'] ?? null,
                    'author_url' => $data['author_url'] ?? null,
                    'thumbnail_width' => $data['thumbnail_width'] ?? null,
                    'thumbnail_height' => $data['thumbnail_height'] ?? null,
                    'html' => $data['html'] ?? null,
                ],
                'fetched_at' => now(),
            ];
        } catch (\Throwable $e) {
            logger()->error('YouTube metadata failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function redditMetadata(string $url): ?array
    {
        $host = parse_url($url, PHP_URL_HOST);

        if (! $host || ! str_contains($host, 'reddit.com')) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        $title = 'Reddit post';
        $subreddit = null;
        $postId = null;

        if ($path) {
            $parts = array_values(array_filter(explode('/', $path)));

            $rIndex = array_search('r', $parts);
            $commentsIndex = array_search('comments', $parts);

            if ($rIndex !== false && isset($parts[$rIndex + 1])) {
                $subreddit = $parts[$rIndex + 1];
            }

            if ($commentsIndex !== false && isset($parts[$commentsIndex + 1])) {
                $postId = $parts[$commentsIndex + 1];
            }

            if ($commentsIndex !== false && isset($parts[$commentsIndex + 2])) {
                $slug = $parts[$commentsIndex + 2];

                $title = str_replace(['_', '-'], ' ', $slug);
                $title = ucfirst($title);
            }
        }

        return [
            'type' => 'link',
            'title' => $title,
            'description' => $subreddit ? 'r/' . $subreddit : null,
            'image_url' => null,
            'site_name' => 'Reddit',
            'provider_name' => 'Reddit',
            'final_url' => $url,
            'favicon_url' => 'https://www.redditstatic.com/desktop2x/img/favicon/favicon-32x32.png',
            'metadata' => [
                'provider' => 'reddit',
                'blocked' => true,
                'subreddit' => $subreddit,
                'post_id' => $postId,
            ],
            'fetched_at' => now(),
        ];
    }

    private function openGraphMetadata(string $url): ?array
    {
        try {
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; Bookmarkr/1.0)',
                'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            ])
                ->timeout(10)
                ->connectTimeout(5)
                ->retry(2, 500)
                ->get($url);
            logger()->info('OpenGraph response', [
                'url' => $url,
                'status' => $response->status(),
                'final_url' => (string) $response->effectiveUri(),
                'content_type' => $response->header('content-type'),
                'body_start' => substr($response->body(), 0, 1000),
            ]);

            if (! $response->successful()) {
                return null;
            }

            $html = $response->body();
            $finalUrl = (string) $response->effectiveUri();

            libxml_use_internal_errors(true);

            $dom = new DOMDocument();
            $dom->loadHTML($html);

            $xpath = new DOMXPath($dom);

            $meta = function ($query) use ($xpath) {
                $node = $xpath->query($query)->item(0);

                return $node
                    ? trim($node->getAttribute('content'))
                    : null;
            };

            $titleNode = $xpath->query('//title')->item(0);

            $title =
                $meta('//meta[@property="og:title"]') ?:
                $meta('//meta[@name="twitter:title"]') ?:
                ($titleNode ? trim($titleNode->textContent) : null);

            $description =
                $meta('//meta[@property="og:description"]') ?:
                $meta('//meta[@name="twitter:description"]') ?:
                $meta('//meta[@name="description"]');

            $image =
                $meta('//meta[@property="og:image"]') ?:
                $meta('//meta[@name="twitter:image"]');

            $siteName =
                $meta('//meta[@property="og:site_name"]') ?:
                parse_url($finalUrl, PHP_URL_HOST);

            $ogType = $meta('//meta[@property="og:type"]');

            return [
                'final_url' => $finalUrl,
                'title' => $title,
                'description' => $description,
                'image_url' => $this->absoluteUrl($image, $finalUrl),
                'favicon_url' => $this->findFavicon($xpath, $finalUrl),
                'site_name' => $siteName,
                'provider_name' => $siteName,
                'type' => str_contains((string) $ogType, 'video') ? 'video' : null,
                'metadata' => [
                    'provider' => 'opengraph',
                    'og_type' => $ogType,
                ],
                'fetched_at' => now(),
            ];
        } catch (\Throwable $e) {
            logger()->error('OpenGraph metadata failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function findFavicon(DOMXPath $xpath, string $baseUrl): ?string
    {
        $queries = [
            '//link[contains(@rel, "apple-touch-icon")]',
            '//link[contains(@rel, "shortcut icon")]',
            '//link[contains(@rel, "icon")]',
        ];

        foreach ($queries as $query) {
            $node = $xpath->query($query)->item(0);

            if ($node) {
                return $this->absoluteUrl(
                    $node->getAttribute('href'),
                    $baseUrl
                );
            }
        }

        return $this->absoluteUrl('/favicon.ico', $baseUrl);
    }

    private function absoluteUrl(?string $url, string $baseUrl): ?string
    {
        if (! $url) {
            return null;
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        $base = parse_url($baseUrl);

        if (! isset($base['scheme'], $base['host'])) {
            return null;
        }

        if (str_starts_with($url, '//')) {
            return $base['scheme'] . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $base['scheme'] . '://' . $base['host'] . $url;
        }

        return $base['scheme'] . '://' . $base['host'] . '/' . ltrim($url, '/');
    }
}
