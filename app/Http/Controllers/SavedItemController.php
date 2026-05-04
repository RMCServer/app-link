<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SavedItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use DOMDocument;
use DOMXPath;
use Illuminate\Support\Facades\Http;

class SavedItemController extends Controller
{
    public function index(Request $request)
    {
        $account = $this->activeAccount();

        $type = $request->query('type');

        $items = $account->savedItems()
            ->with('category')
            ->when(in_array($type, ['link', 'video', 'image']), function ($query) use ($type) {
                $query->where('type', $type);
            })
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('pages.items.index', compact('items', 'account', 'type'));
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
            $response = Http::timeout(5)->get($validated['source_url']);

            if ($response->successful()) {
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

                // favicon
                $favicon = null;

                $faviconNode = $xpath->query(
                    '//link[contains(@rel, "icon")]'
                )->item(0);

                if ($faviconNode) {
                    $favicon = $faviconNode->getAttribute('href');
                }

                if (! $favicon) {
                    $favicon = '/favicon.ico';
                }

                if ($favicon && ! str_starts_with($favicon, 'http')) {
                    $base = parse_url($finalUrl);

                    if (str_starts_with($favicon, '//')) {
                        $favicon = $base['scheme'] . ':' . $favicon;
                    } elseif (str_starts_with($favicon, '/')) {
                        $favicon = $base['scheme'] . '://' . $base['host'] . $favicon;
                    } else {
                        $favicon = $base['scheme'] . '://' . $base['host'] . '/' . $favicon;
                    }
                }

                $validated['final_url'] = $validated['final_url'] ?? $finalUrl;
                $validated['title'] = $validated['title'] ?? $title;
                $validated['description'] = $validated['description'] ?? $description;
                $validated['image_url'] = $validated['image_url'] ?? $image;
                $validated['favicon_url'] = $validated['favicon_url'] ?? $favicon;
                $validated['site_name'] = $validated['site_name'] ?? $siteName;
                $validated['provider_name'] = $validated['provider_name'] ?? $siteName;
                $validated['fetched_at'] = now();

                $validated['metadata'] = [
                    'og_type' => $ogType,
                ];

                if (str_contains((string) $ogType, 'video')) {
                    $validated['type'] = 'video';
                }
            }
        }

        $validated['account_id'] = $account->id;
        $validated['created_by_user_id'] = auth()->id();
        $validated['is_favorite'] = $request->boolean('is_favorite');
        $validated['is_archived'] = $request->boolean('is_archived');

        $item = SavedItem::create($validated);

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
            ->route('saved-items.show', $savedItem)
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
