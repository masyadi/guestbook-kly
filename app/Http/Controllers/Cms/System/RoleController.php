<?php
namespace App\Http\Controllers\Cms\System;

use App\Http\Controllers\Controller,
    App\Models\Role, Helper, DataTable,
    App\Models\RoleMenu,
    Illuminate\Validation\Rule;

use \App\Events\OnSaved;

class RoleController extends Controller
{
    /**
     * LIST 
    **/
    public function index()
    {
        if (request()->ajax()) 
        {
            return DataTable::of(Role::where('status', '<>', '-1'))
                    ->addColumn('action', function ($r) { return Helper::action($r);})
                    ->editColumn('status', function ($r) { return Helper::status($r);})
                    ->editColumn('created_at', function ($r) { return Helper::formatDate($r->created_at, 'id');})
                    ->rawColumns(['action', 'created_at', 'status'])
                    ->toJson();
        }

        return view('CMS::page.system.role.index');
    }

    /**
     * ADD NEW
    **/
    public function create()
    {
        return view('CMS::page.system.role.form');
    }
    
    /**
     * ROW EDIT BY ID 
    **/
    public function edit($id)
    {
        $row = Role::with(['relaccess'])->find($id);

        if( !$row ) return abort(404);
        $dtMenu = $row->relaccess->pluck('menu_id', 'menu_id');
        $dtMenu = $dtMenu->toArray();

        return view('CMS::page.system.role.form', compact('row', 'dtMenu'));
    }
    
    /**
     * ROW DELETE BY ID 
    **/
    public function destroy($id)
    {
        $ret = ['status'=>false, 'message'=> 'Delete operation on resource has failed'];
        
        if(Role::find($id)->update(['status'=>'-1']))
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
            'name'=> [
                'required', 'min:2', 'max:150',
                Rule::unique('roles')->where(function ($q) {
                    $q->where('status', '<>', '-1');
                })->ignore(request('id'))   
            ]
        ];

        //custom
        $menu = request('menu');
        request()->request->add(['status'=>request('status')?'1':'0']);
        request()->request->remove('menu');

        if(!request('id')) request()->request->add(['slug'=>\Str::slug(request('name'))]);
        

        return Helper::save(Role::class, $validation, function($request, $data, $redirect) use($menu) {

            //delete old data
            if( isset($data->id) )
            {
                RoleMenu::where('role_id', $data->id)->delete();

                if( $menu )
                {
                    $dtIns = [];

                    foreach( $menu as $r)
                    {
                        $dtIns[] = [
                            'menu_id'=> $r,
                            'role_id'=> $data->id,
                        ];
                    }

                    RoleMenu::insert($dtIns);
                }

                Helper::sessionLogin(\Auth::user()->id);
            }

            return $redirect;

        }, request('id'), 'name');
    }
}