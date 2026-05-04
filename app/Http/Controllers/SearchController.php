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

        /*
        |--------------------------------------------------------------------------
        | Saved items search
        |--------------------------------------------------------------------------
        | Search fields:
        | - type
        | - title
        | - site_name
        |
        | Filters:
        | - type
        | - category
        |
        | Sort:
        | - date
        */
        $savedItems = DB::table('saved_items')
            ->leftJoin('categories', 'saved_items.category_id', '=', 'categories.id')
            ->where('saved_items.account_id', $account->id)
            ->whereNull('saved_items.deleted_at')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('saved_items.type', 'like', "%{$search}%")
                        ->orWhere('saved_items.title', 'like', "%{$search}%")
                        ->orWhere('saved_items.site_name', 'like', "%{$search}%");
                });
            })
            ->when(in_array($type, ['link', 'video', 'image']), function ($query) use ($type) {
                $query->where('saved_items.type', $type);
            })
            ->when($categoryId, function ($query) use ($categoryId, $account) {
                $query
                    ->where('saved_items.category_id', $categoryId)
                    ->where('categories.account_id', $account->id);
            })
            ->select([
                'saved_items.id',
                'saved_items.type',
                'saved_items.title',
                'saved_items.description',
                'saved_items.site_name',
                'saved_items.source_url',
                'saved_items.final_url',
                'saved_items.image_url',
                'saved_items.favicon_url',
                'saved_items.created_at',
                'categories.name as category_name',
                DB::raw("'saved_item' as result_type"),
            ])
            ->when($sort === 'oldest', function ($query) {
                $query->orderBy('saved_items.created_at', 'asc');
            }, function ($query) {
                $query->orderBy('saved_items.created_at', 'desc');
            })
            ->limit(50)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Categories search
        |--------------------------------------------------------------------------
        | Search field:
        | - name
        |
        | Filter:
        | - category
        */
        $categoryResults = DB::table('categories')
            ->where('account_id', $account->id)
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query) use ($categoryId) {
                $query->where('id', $categoryId);
            })
            ->select([
                'id',
                'name',
                'slug',
                'created_at',
                DB::raw("'category' as result_type"),
            ])
            ->when($sort === 'oldest', function ($query) {
                $query->orderBy('created_at', 'asc');
            }, function ($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->limit(50)
            ->get();

        return view('pages.search.index', [
            'account' => $account,
            'categories' => $categories,
            'savedItems' => $savedItems,
            'categoryResults' => $categoryResults,
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
