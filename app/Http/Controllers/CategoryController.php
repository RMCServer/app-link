<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SavedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $account = $this->activeAccount();

        $allItemsCount = $account->savedItems()->count();

        $categories = $account->categories()
            ->withCount('savedItems')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('pages.categories.index', compact('categories', 'account', 'allItemsCount'));
    }

    public function create()
    {
        $account = $this->activeAccount();

        $categories = $account->categories()
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('categories.create', compact('categories', 'account'));
    }

    public function store(Request $request)
    {
        $account = $this->activeAccount();

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('account_id', $account->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $slug = $this->uniqueSlug($account->id, $validated['name']);

        $category = $account->categories()->create([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'color' => $validated['color'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('categories.index', $category)
            ->with('success', 'Category created.');
    }

    public function show(Category $category)
    {
        $this->authorizeAccountAccess($category);

        $items = $category->savedItems()
            ->with('category')
            ->latest()
            ->paginate(20);

        return view('categories.show', [
            'category' => $category,
            'items' => $items,
        ]);
    }

    public function edit(Category $category)
    {
        $this->authorizeAccountAccess($category);

        $account = $this->activeAccount();

        $categories = $account->categories()
            ->where('id', '!=', $category->id)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('categories.edit', compact('category', 'categories', 'account'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeAccountAccess($category);

        $account = $this->activeAccount();

        $validated = $request->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('categories', 'id')
                    ->where('account_id', $account->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'color' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ]);

        $category->update([
            'parent_id' => $validated['parent_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $category->name !== $validated['name']
                ? $this->uniqueSlug($account->id, $validated['name'], $category->id)
                : $category->slug,
            'color' => $validated['color'] ?? null,
            'sort_order' => $validated['sort_order'] ?? 0,
        ]);

        return redirect()
            ->route('categories.show', $category)
            ->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeAccountAccess($category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', 'Category deleted.');
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

    private function authorizeAccountAccess(Category $category): void
    {
        $account = $this->activeAccount();

        abort_unless(
            $category->account_id === $account->id,
            403,
            'You cannot access categories from another account.'
        );
    }

    private function uniqueSlug(int $accountId, string $name, ?int $ignoreId = null): string
    {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $count = 2;

        while (
            Category::where('account_id', $accountId)
                ->where('slug', $slug)
                ->when($ignoreId, fn ($query) => $query->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }
}
