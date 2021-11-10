<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\GuestBook;
use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    public function index()
    {
        return view('WEB::page.home');
    }

    public function guestBook(Request $request)
    {
        if( $request->isMethod('post') )
        {
            $request->validate([
                'first_name' => ['required', 'min:2', 'max:75'],
                'last_name' => ['required', 'min:2', 'max:75'],
                'organization' => ['required', 'min:2', 'max:100'],
                'province' => ['required'],
                'city' => ['required'],
                'address' => ['required', 'min:3'],
            ]);

            if( GuestBook::insert([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'organization' => $request->organization,
                'province' => $request->province,
                'city' => $request->city,
                'address' => $request->address,
            ]) )
            {
                Session::flash('success', 'Data sent successfully');
                return;
            }
            Session::flash('error', 'Data failed to send');
            return;
        }

        return view('WEB::page.guest_book.index');
    }
}
