<?php
namespace App\Http\Controllers\Cms\System;

use App\Http\Controllers\Controller,
    App\Models\Help, Helper, DataTable,
    Illuminate\Validation\Rule;

use \App\Events\OnSaved;

class HelpController extends Controller
{
    /**
     * LIST 
    **/
    public function index()
    {
        if (request()->ajax()) 
        {
            return DataTable::of(Help::where('status', '<>', '-1'))
                    ->addColumn('action', function ($r) { return Helper::action($r);})
                    ->editColumn('status', function ($r) { return Helper::status($r);})
                    ->editColumn('created_at', function ($r) { return Helper::formatDate($r->created_at, 'id');})
                    ->rawColumns(['action', 'created_at', 'status'])
                    ->toJson();
        }

        return view('CMS::page.system.help.index');
    }

    /**
     * ADD NEW
    **/
    public function create()
    {
        return view('CMS::page.system.help.form');
    }
    
    /**
     * ROW EDIT BY ID 
    **/
    public function edit($id)
    {
        $row = Help::find($id);

        if( !$row ) return abort(404);

        return view('CMS::page.system.help.form', compact('row'));
    }
    
    /**
     * ROW DELETE BY ID 
    **/
    public function destroy($id)
    {
        $ret = ['status'=>false, 'message'=> 'Delete operation on resource has failed'];
        
        if(Help::find($id)->update(['status'=>'-1']))
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
            'path'=> [
                'required', 'min:2', 'max:255',
                Rule::unique('helps')->where(function ($q) {
                    $q->where('status', '<>', '-1');
                })->ignore(request('id'))   
            ],
            'content'=> 'required'
        ];

        //custom
        request()->request->add(['status'=>request('status')?'1':'0']);
        
        return Helper::save(Help::class, $validation, function($request, $data, $redirect) {

            return $redirect;

        }, request('id'));
    }
}