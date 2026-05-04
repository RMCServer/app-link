<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SavedItem;
use App\Services\LinkMetadataService;
use Illuminate\Http\Request;
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

    public function store(Request $request, LinkMetadataService $metadataService)
    {
        $account = $this->activeAccount();

        $validated = $this->validateSavedItem($request, $account->id);

        if (! empty($validated['source_url'])) {
            $metadata = $metadataService->fetch($validated['source_url']);

            $validated = $metadataService->merge($validated, $metadata);
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

        return view('pages.items.edit', [
            'item' => $savedItem,
            'categories' => $categories,
            'account' => $account,
        ]);
    }

    public function update(Request $request, SavedItem $savedItem)
    {
        $this->authorizeAccountAccess($savedItem);

        $account = $this->activeAccount();
        $request->type = $savedItem->type;
        $validated = $this->validateSavedItem($request, $account->id);

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

    private function validateSavedItem(Request $request, int $accountId): array
    {
        return $request->validate([
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('account_id', $accountId),
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
