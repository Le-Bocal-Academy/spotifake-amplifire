<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use App\Models\Account;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    // protected function redirectTo(Request $request): ?string
    // {
    //     return $request->expectsJson() ? null : route('verification.verify', ['id' => $request->id, 'hash' => $request->hash]);
    //     return $request->expectsJson() ? null : route('verification.verify');
    // }

    protected function redirectTo(Request $request): ?string
    {
        if (!$request->expectsJson()) {
            $account = Account::where('email', $request->email)->first();
            $id = $account->getKey();
            $hash = sha1($account->getEmailForVerification());

            return route('verification.verify', ['id' => $id, 'hash' => $hash]);
        }

        return null;
    }
}
