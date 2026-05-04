<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SavedItem;
use DOMDocument;
use DOMXPath;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;

class SavedItemController extends Controller
{
    public function index(Request $request)
    {
        $account = $this->activeAccount();

        $type = $request->query('type');
        $categoryId = $request->query('category');

        $categories = $account->categories()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        $items = $account->savedItems()
            ->with('category')
            ->when(in_array($type, ['link', 'video', 'image']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->when($categoryId, function ($query) use ($categoryId, $account) {
                $query->whereHas('category', function ($query) use ($categoryId, $account) {
                    $query
                        ->where('id', $categoryId)
                        ->where('account_id', $account->id);
                });
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.items.index', compact(
            'items',
            'account',
            'type',
            'categories',
            'categoryId'
        ));
    }

    public function create()
    {
        $account = $this->activeAccount();

        $categories = Category::where('account_id', $account->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.items.create', compact('categories', 'account'));
    }

    public function store(Request $request)
    {
        $account = $this->activeAccount();

        $validated = $request->validate([
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('account_id', $account->id),
            ],
            'type' => ['required', Rule::in(['link', 'video', 'image'])],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url'],
            'final_url' => ['nullable', 'url'],
            'image_url' => ['nullable', 'url'],
            'favicon_url' => ['nullable', 'url'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        if (! empty($validated['source_url'])) {
            $metadata = $this->fetchMetadata($validated['source_url']);

            $validated = $this->mergeMetadata($validated, $metadata);
        }

        $validated['account_id'] = $account->id;
        $validated['created_by_user_id'] = auth()->id();
        $validated['is_favorite'] = $request->boolean('is_favorite');
        $validated['is_archived'] = $request->boolean('is_archived');

        SavedItem::create($validated);

        return redirect()
            ->route('index')
            ->with('success', 'Item saved.');
    }

    public function show(SavedItem $savedItem)
    {
        $this->authorizeAccountAccess($savedItem);

        $savedItem->load(['category', 'tags']);

        return view('pages.items.show', [
            'item' => $savedItem,
        ]);
    }

    public function edit(SavedItem $savedItem)
    {
        $this->authorizeAccountAccess($savedItem);

        $account = $this->activeAccount();

        $categories = Category::where('account_id', $account->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('saved-items.edit', [
            'item' => $savedItem,
            'categories' => $categories,
            'account' => $account,
        ]);
    }

    public function update(Request $request, SavedItem $savedItem)
    {
        $this->authorizeAccountAccess($savedItem);

        $account = $this->activeAccount();

        $validated = $request->validate([
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('account_id', $account->id),
            ],
            'type' => ['required', Rule::in(['link', 'video', 'image'])],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'source_url' => ['nullable', 'url'],
            'final_url' => ['nullable', 'url'],
            'image_url' => ['nullable', 'url'],
            'favicon_url' => ['nullable', 'url'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'provider_name' => ['nullable', 'string', 'max:255'],
            'file_path' => ['nullable', 'string', 'max:255'],
            'mime_type' => ['nullable', 'string', 'max:255'],
            'metadata' => ['nullable', 'array'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_archived' => ['nullable', 'boolean'],
        ]);

        $validated['is_favorite'] = $request->boolean('is_favorite');
        $validated['is_archived'] = $request->boolean('is_archived');

        unset($validated['account_id']);
        unset($validated['created_by_user_id']);

        $savedItem->update($validated);

        return redirect()
            ->route('show', $savedItem)
            ->with('success', 'Item updated.');
    }

    public function delete(SavedItem $savedItem)
    {
        $this->authorizeAccountAccess($savedItem);

        $savedItem->delete();

        return redirect()
            ->route('index')
            ->with('success', 'Item deleted.');
    }

    private function fetchMetadata(string $url): array
    {
        return $this->youtubeOembed($url)
            ?? $this->redditMetadata($url)
            ?? $this->openGraphMetadata($url)
            ?? [];
    }

    private function mergeMetadata(array $validated, array $metadata): array
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
            \Log::error('YouTube metadata failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

//     private function redditMetadata(string $url): ?array
//     {
//         $host = parse_url($url, PHP_URL_HOST);
//
//         $isReddit = $host && str_contains($host, 'reddit.com');
//
//         if (! $isReddit) {
//             return null;
//         }
//
//         $jsonUrl = rtrim($url, '/') . '.json';
//
//         try {
//             $response = Http::withHeaders([
//                 'User-Agent' => 'Bookmarkr/1.0',
//                 'Accept' => 'application/json',
//             ])
//                 ->timeout(10)
//                 ->connectTimeout(5)
//                 ->get($jsonUrl);
//
//             if (! $response->successful()) {
//                 return null;
//             }
//
//             $json = $response->json();
//
//             $post = $json[0]['data']['children'][0]['data'] ?? null;
//
//             if (! $post) {
//                 return null;
//             }
//
//             $image = null;
//
//             if (! empty($post['preview']['images'][0]['source']['url'])) {
//                 $image = html_entity_decode($post['preview']['images'][0]['source']['url']);
//             } elseif (! empty($post['thumbnail']) && str_starts_with($post['thumbnail'], 'http')) {
//                 $image = $post['thumbnail'];
//             }
//
//             return [
//                 'type' => ! empty($post['is_video']) ? 'video' : 'link',
//                 'title' => $post['title'] ?? null,
//                 'description' => ! empty($post['selftext'])
//                     ? substr($post['selftext'], 0, 500)
//                     : null,
//                 'image_url' => $image,
//                 'site_name' => 'Reddit',
//                 'provider_name' => 'Reddit',
//                 'final_url' => ! empty($post['permalink'])
//                     ? 'https://www.reddit.com' . $post['permalink']
//                     : $url,
//                 'favicon_url' => 'https://www.redditstatic.com/desktop2x/img/favicon/favicon-32x32.png',
//                 'metadata' => [
//                     'provider' => 'reddit',
//                     'subreddit' => $post['subreddit'] ?? null,
//                     'author' => $post['author'] ?? null,
//                     'score' => $post['score'] ?? null,
//                     'num_comments' => $post['num_comments'] ?? null,
//                     'permalink' => $post['permalink'] ?? null,
//                     'reddit_url' => $post['url'] ?? null,
//                     'is_video' => $post['is_video'] ?? false,
//                 ],
//                 'fetched_at' => now(),
//             ];
//         } catch (\Throwable $e) {
//             \Log::error('Reddit metadata failed', [
//                 'url' => $url,
//                 'error' => $e->getMessage(),
//             ]);
//
//             return null;
//         }
//     }
    private function redditMetadata(string $url): ?array
    {
        $host = parse_url($url, PHP_URL_HOST);

        $isReddit = $host && str_contains($host, 'reddit.com');

        if (! $isReddit) {
            return null;
        }

        $path = parse_url($url, PHP_URL_PATH);

        $title = 'Reddit post';
        $subreddit = null;
        $postId = null;

        if ($path) {
            $parts = array_values(array_filter(explode('/', $path)));

            // Example:
            // r/forbiddensnacks/comments/1t2z242/forbidden_giant_cake
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

            $favicon = $this->findFavicon($xpath, $finalUrl);

            return [
                'final_url' => $finalUrl,
                'title' => $title,
                'description' => $description,
                'image_url' => $this->absoluteUrl($image, $finalUrl),
                'favicon_url' => $favicon,
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
            \Log::error('OpenGraph metadata failed', [
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

    private function activeAccount()
    {
        $accountId = session('active_account_id');

        abort_unless($accountId, 403, 'No active account selected.');

        return auth()->user()
            ->accounts()
            ->where('accounts.id', $accountId)
            ->firstOrFail();
    }

    private function authorizeAccountAccess(SavedItem $savedItem): void
    {
        $account = $this->activeAccount();

        abort_unless(
            $savedItem->account_id === $account->id,
            403,
            'You cannot access items from another account.'
        );
    }
}
