<?php
namespace App\Http\Controllers\Cms;

use App\Http\Controllers\AuthController as AppAuthController;
use App\Http\Controllers\Controller;
use Auth;
use Helper;

class AuthController extends AppAuthController
{
    public function login()
    {
        if( request()->isMethod('post') )
        {
            return $this->authUser('cms');
        }

        return view('CMS::page.auth.login');
    }

    public function logout()
    {
        Auth::guard('cms')->logout();
        return redirect(Helper::CMS(''));
    }
}