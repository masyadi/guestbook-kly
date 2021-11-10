<?php
namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller,
	App\User, Helper,
    Illuminate\Validation\Rule;


class ProfileController extends Controller
{
	function index()
	{
		if ( \Request::isMethod('post') )
		{
			$userID = Helper::userId();

			$validation = [
	            'name'=> [
	                'required', 'min:2', 'max:150' 
	            ],
	            'email'=> [
	                'required', 'email', 'min:2', 'max:150',
	                Rule::unique('users')->where(function ($q) {})->ignore($userID)   
	            ]
	        ];

	        $repassword = request('repassword');
	        request()->request->remove('repassword');

			//password
			if( request('password') == $repassword  )
	        {
		        if( request('password') && $repassword )
		        {
		            request()->request->add(['password'=>\Hash::make(request('password'))]);
		        }
		        else request()->request->remove('password');
		    }
	        else return back()->with('error', __('Password Confirmation not match'));
	        //password

	        return Helper::save(User::class, $validation, function($request, $data, $redirect) {

	        	if( isset($data->id) )
	        	{
	        		Helper::sessionLogin($data->id);
	        	}

	        	return $redirect;

	        }, $userID);
		}
		else return view('CMS::page.profile', ['row' => Helper::user()]);
	}
}