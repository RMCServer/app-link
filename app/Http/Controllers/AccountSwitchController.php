<?php

namespace App\Http\Controllers;

use App\Models\Account;
use Illuminate\Http\Request;

class AccountSwitchController extends Controller
{
    public function index()
    {
        $accounts = auth()->user()
            ->accounts()
            ->orderBy('accounts.name')
            ->get();

        $activeAccountId = session('active_account_id');

        return view('pages.settings.change', compact('accounts', 'activeAccountId'));
    }

    public function switch(Account $account)
    {
        $userCanAccessAccount = auth()->user()
            ->accounts()
            ->where('accounts.id', $account->id)
            ->exists();

        abort_unless($userCanAccessAccount, 403);

        session([
            'active_account_id' => $account->id,
        ]);

        return redirect()
            ->route('accounts.switch.index')
            ->with('success', 'Account changed to ' . $account->name . '.');
    }
}
