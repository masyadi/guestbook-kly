<?php
namespace App\Http\Controllers\Cms\System;

use App\Http\Controllers\Controller,
    App\Models\Menu, Helper,
    App\Models\RoleMenu,
    Illuminate\Validation\Rule;

class MenuController extends Controller
{
    /**
     * LIST 
    **/
    public function index()
    {
        return view('CMS::page.system.menu.main');
    }

    /**
     * ADD NEW
    **/
    public function create()
    {
        return view('CMS::page.system.menu.form');
    }
    
    /**
     * ROW EDIT BY ID 
    **/
    public function edit($id)
    {
        $row = Menu::with('relrole')->find($id);

        if( !$row ) return abort(404);

        $roles = $row->relrole->pluck('role_id', 'role_id');
        $roles = $roles->toArray();

        return view('CMS::page.system.menu.form', compact('row', 'roles'));
    }
    
    /**
     * ROW DELETE BY ID 
    **/
    public function destroy($id)
    {
        $ret = ['status'=>false, 'message'=> 'Delete operation on resource has failed'];
        
        if(Menu::find($id)->delete())
        {
            Helper::sessionLogin(Helper::userId());

            $ret = ['status'=>true, 'message'=> 'Resource has been deleted succesfully'];        
        }
        
        return response()->json($ret);
    }
    
    /**
     * SAVE
    **/
    public function store()
    {
        //SORTING
        if( request('sort') )
        {
            $sorted = json_decode(request('sort'), true);
            
            return $this->_saveOrder($sorted);
        }
        
        $validation = [
            'name'=> ['required', 'min:2', 'max:150']
        ];

        //custom request
        $roles = request('roles');
        request()->request->add(['status'=>request('status')?'1':'0']);
        request()->request->remove('roles');

        return Helper::save(Menu::class, $validation, function($request, $data, $redirect) use($roles) {

            //delete old data
            if( isset($data->id) )
            {
                RoleMenu::where('menu_id', $data->id)->delete();

                if( $roles )
                {
                    $dtIns = [];

                    foreach( $roles as $r)
                    {
                        $dtIns[] = [
                            'role_id'=> $r,
                            'menu_id'=> $data->id,
                        ];
                    }

                    RoleMenu::insert($dtIns);
                }

                Helper::sessionLogin(Helper::userId());
            }

            return $redirect;

        }, request('id'));
    }
    
    private function _saveOrder($data, $parent=0)
    {
        if( $data )
        {
            foreach( $data as $k=>$d )
            {
                Menu::where('id', $d['id'])->update(['order'=>$k, 'parent'=>$parent]);
                
                if( isset($d['children']) )
                {
                    $this->_saveOrder($d['children'], $d['id']);
                }
            }
        }

        Helper::sessionLogin(Helper::userId());
        
        return '1';
    }
}