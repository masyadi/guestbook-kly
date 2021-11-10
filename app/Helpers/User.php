<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

Trait User
{
    static public function user()
    {
        return Auth::guard(self::activeGuard())->user() ?: null;
    }

    static public function userId()
    {
        return self::user()->id ?: null;
    }

    static public function activeGuard()
    {
        foreach(array_keys(config('auth.guards')) as $guard)
        {
            if( auth()->guard($guard)->check() )
            {
                return $guard;
            }
        }

        return null;
    }
}