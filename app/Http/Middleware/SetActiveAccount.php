<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetActiveAccount
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && ! session()->has('active_account_id')) {
            $firstAccount = auth()->user()
                ->accounts()
                ->orderBy('accounts.id')
                ->first();

            if ($firstAccount) {
                session(['active_account_id' => $firstAccount->id]);
            }
        }

        return $next($request);
    }
}
