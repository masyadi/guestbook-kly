<?php
namespace App\Http\Controllers\Cms\System;

use App\Http\Controllers\Controller,
    App\User, Helper, DataTable,
    Illuminate\Validation\Rule;
use App\Models\Role;

class UserController extends Controller
{
    /**
     * LIST 
    **/
    public function index()
    {
        if (request()->ajax()) 
        {
            $rows = User::select('users.*')
                ->where('users.status', '<>', '-1')
                ->with(['relrole']);

            return DataTable::of($rows)
                ->addColumn('action', function ($r) { return Helper::action($r);})
                ->editColumn('logged_in_at', function ($r) { return $r->logged_in_at ? Helper::formatDate($r->logged_in_at, "d M Y H:i") : '-';})
                ->editColumn('status', function ($r) { return Helper::status($r);})
                ->editColumn('email', function ($r) { return $r->email.($r->email_verified_at ? Helper::verified($r->email_verified_at) : '');})
                ->rawColumns(['email', 'action'])
                ->toJson();
        }

        return view('CMS::page.system.user.index');
    }

    /**
     * ADD NEW
    **/
    public function create()
    {
        return view('CMS::page.system.user.form');
    }
    
    /**
     * ROW EDIT BY ID 
    **/
    public function edit($id)
    {
        $row = User::find($id);

        if( !$row ) return abort(404);
        
        return view('CMS::page.system.user.form', compact('row'));
    }
    
    /**
     * ROW DELETE BY ID 
    **/
    public function destroy($id)
    {
        $ret = ['status'=>false, 'message'=> 'Delete operation on resource has failed'];
        
        if(User::find($id)->delete())
        {
            $ret = ['status'=>true, 'message'=> 'Resource has been deleted succesfully'];        
        }
        
        return response()->json($ret);
    }
    
    /**
     * SAVE
    **/
    public function store()
    {
        $validation = [
            'email'=> [
                'required', 'email', 'min:2', 'max:150',
                Rule::unique('users')->where(function ($q) {
                    $q->where('status', '<>', '-1');
                })->ignore(request('id'))   
            ]
        ];


        $invite = request('invite');

        //checkbox
        request()->request->add(['status'=>request('status')?'1':'0']);
        request()->request->remove('invite');

        //password
        if( request('password') )
        {
            request()->request->add(['password'=>\Hash::make(request('password'))]);
        }
        else request()->request->remove('password');
        //password

        return Helper::save(User::class, $validation, function($request, $data, $redirect) use($invite) {

            if( $invite && $data)
            {
                \Notification::send(User::with('relrole')->find($data->id), 
                    new \App\Notifications\userInvitation(\Auth::user()));
            }

            return $redirect;

        }, request('id'));
    }
}
