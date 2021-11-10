<?php
namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * INDEX 
    **/
    function index()
    {
        return view('CMS::page.dashboard');
    }
}