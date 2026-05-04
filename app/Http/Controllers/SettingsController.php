<?php

namespace App\Http\Controllers;

use App\Models\SavedItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $account = $this->activeAccount();

        $savedItemsCount = $account->savedItems()->count();
        $categoriesCount = $account->categories()->count();

        return view('pages.settings.index', compact(
            'account',
            'savedItemsCount',
            'categoriesCount'
        ));
    }

    public function deleteSavedData()
    {
        $account = $this->activeAccount();

        // Alleen data van het actieve account verwijderen
        $account->savedItems()->delete();

        return redirect()
            ->route('settings.index')
            ->with('success', 'All saved items have been deleted.');
    }

    public function deleteAccount(Request $request)
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
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
