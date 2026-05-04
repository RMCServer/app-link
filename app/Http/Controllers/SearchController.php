<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $account = $this->activeAccount();

        $search = $request->query('q');
        $type = $request->query('type');
        $categoryId = $request->query('category');
        $sort = $request->query('sort', 'newest');

        $categories = DB::table('categories')
            ->where('account_id', $account->id)
            ->orderBy('name')
            ->get();

        $savedItemsQuery = DB::table('saved_items')
            ->leftJoin('categories', 'saved_items.category_id', '=', 'categories.id')
            ->where('saved_items.account_id', $account->id)
            ->whereNull('saved_items.deleted_at')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('saved_items.type', 'like', "%{$search}%")
                        ->orWhere('saved_items.title', 'like', "%{$search}%")
                        ->orWhere('saved_items.site_name', 'like', "%{$search}%")
                        ->orWhere('categories.name', 'like', "%{$search}%");
                });
            })
            ->when(in_array($type, ['link', 'video', 'image']), function ($query) use ($type) {
                $query->where('saved_items.type', $type);
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('saved_items.category_id', $categoryId);
            })
            ->select([
                'saved_items.id',
                DB::raw("'saved_item' as result_type"),
                'saved_items.type as item_type',
                'saved_items.title',
                'saved_items.description',
                'saved_items.site_name',
                'saved_items.source_url',
                'saved_items.final_url',
                'saved_items.image_url',
                'saved_items.favicon_url',
                'saved_items.category_id',
                'categories.name as category_name',
                'saved_items.created_at',
            ]);

        $categoriesQuery = DB::table('categories')
            ->where('categories.account_id', $account->id)
            ->when($search, function ($query) use ($search) {
                $query->where('categories.name', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('categories.id', $categoryId);
            })
            ->select([
                'categories.id',
                DB::raw("'category' as result_type"),
                DB::raw("'category' as item_type"),
                'categories.name as title',
                DB::raw('NULL as description'),
                DB::raw('NULL as site_name'),
                DB::raw('NULL as source_url'),
                DB::raw('NULL as final_url'),
                DB::raw('NULL as image_url'),
                DB::raw('NULL as favicon_url'),
                'categories.id as category_id',
                'categories.name as category_name',
                'categories.created_at',
            ]);

        $combinedQuery = $savedItemsQuery->unionAll($categoriesQuery);

        $results = DB::query()
            ->fromSub($combinedQuery, 'results')
            ->orderBy('created_at', $sort === 'oldest' ? 'asc' : 'desc')
            ->when(! $search, function ($query) {
                $query->limit(10);
            })
            ->get();

        return view('pages.search.index', [
            'account' => $account,
            'categories' => $categories,
            'results' => $results,
            'search' => $search,
            'type' => $type,
            'categoryId' => $categoryId,
            'sort' => $sort,
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
}
