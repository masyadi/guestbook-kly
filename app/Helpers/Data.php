<?php
namespace App\Helpers;

Trait Data {

    /**
     * $model -> Eloquent Object 
     * $validation -> Validator Array 
     * $callback -> Function Callback
     * $id -> integer
     * $slug -> slug string field name
     * $request -> request data
    **/
	static function save($model, $validation, callable $callback, $id=null, $slug=null, $request=null, $successRedirect=null)
	{
		if( !$request ) $request = request()->except(['_token', '_redirect_params', 'attachment']);

        $v = \Validator::make($request, $validation);
        
        $data = null;
        
        $redirect = null;

        $redirectParams = request('_redirect_params') ? '?'.http_build_query(request('_redirect_params')) : '';
        
        if( $v->fails() )
        {
            if (request()->ajax())
            {
                $redirect = ['type' => 'errors', 'message' => $v->messages()];
            }
            else
            {
                $redirect = back()->withErrors($v)->withInput();
            }
        }
        else
        {
            if( !$successRedirect ) $successRedirect = self::CMS(self::page().$redirectParams);

            //convert base64 to url image
            self::convertBase64toUrl($request);
            
            if( $id )
            {
                //UPDATE
                $data = $model::find($id)->fill($request);
                
                if($data->save())
                {
                    if (request()->ajax())
                    {
                        $redirect = ['type' => 'success', 'message' => __('Data berhasil diupdate')];
                    }
                    else
                    {
                        $redirect = redirect($successRedirect)->with('success', __('Data berhasil diupdate'));
                    }
                }
            }
            else
            {

                if( $slug ) $request['slug'] = \Str::slug(request($slug));

                //INSERT
                if($data = $model::create($request))
                {
                    if (request()->ajax())
                    {
                        $redirect = ['type' => 'success', 'message' => __('Data berhasil disimpan')];
                    }
                    else
                    {
                        $redirect = redirect($successRedirect)->with('success', __('Data berhasil disimpan'));
                    }                    
                }
            }
        }

        //ON SAVED
        if($data && config('app.enabled_socket') ) 
        {
            try 
            {
                broadcast(new \App\Events\OnSaved(self::page(), $data));
            } 
            catch (\Exception $e) 
            {
                
            }
        }

        //DELETE AUTO SAVE
        if( isset($data->id) )
        {
            self::deleteAutoSave();
        }
        
        return $callback($request, $data, $redirect);
	}

    // save with locale data
    static function saveWithChild($model, $items, $slug = null, callable $callback = null)
    {
        request()->request->add($items[0]);

        return self::save($model, [], function($request, $data, $redirect) use ($model, $items, $slug, $callback)
        {
            $lastIndex = count($items) - 1;
            $results = [];

            if( $data )
            {
                $results[] = $data;
            }

            foreach( $items as $index => $item )
            {
                // skip element 0
                if ( $index < 1 ) continue;

                request()->request->add(['parent_id' => $data->id]);
                request()->request->add($item);

                $parent = $model::where('parent_id', $data->id)->first();

                return self::save($model, [], function($request, $child, $redirect) use ($lastIndex, $index, $results, $callback)
                {
                    if( $child )
                    {
                        $results[] = $child;
                    }

                    if( $lastIndex == $index )
                    {
                        if( $callback )
                        {
                            return $callback($request, $results, $redirect);
                        }

                        return $redirect;
                    }
        
                }, $parent->id ?? null, $slug);
            }
            
        }, request('id'), $slug);
    }
    
    static function getMenu()
    {
        $menu = null;
        
        if( $row = \App\Models\Menu::where('status', '<>', '-1')->orderBy('order')->get() )
        {
            foreach( $row as $m )
            {
                $menu['data'][$m->id] = $m->toArray();
                $menu['parent_id'][$m->parent][] = $m->id;
                $menu['id_parent'][$m->id] = $m->parent;
                $menu['slug_id'][$m->slug] = $m->id;
            }    
        }
        
        return $menu;
    }

    /** 
     * @param $userID integer from user id
    **/
    static function sessionLogin($userID)
    {
        $user = \App\User::where('id', $userID)->with(['relrole'])->first();

        //BUILD MENU
        if( $user->relrole && isset($user->relrole->relaccess) )
        {
            $menuID = collect($user->relrole->relaccess)->pluck('relmenu.id', 'relmenu.id');

            $dtMenu = \App\Models\Menu::where('status', '1')->where(function($q) use($menuID) {

                        $q->orWhere('show_all', '1');

                        if( $menuID )
                        {
                            $q->OrwhereIn('id', $menuID->toArray());
                        }
                        
                      })->orderBy('order')->get();

            $menu = [];

            if( $dtMenu )
            {
                foreach( $dtMenu as $m )
                {
                    $menu['data'][$m->id] = $m->toArray();
                    $menu['parent_id'][$m->parent][] = $m->id;
                    $menu['id_parent'][$m->id] = $m->parent;
                    $menu['slug_id'][$m->slug] = $m->id;
                }
            }

            \Session::put('menu', $menu);
            \Session::put('user', $user);
        }
    }

    /** 
     * FORMAT TOKEN INPUT
     * @param $model object
     * @param $id integer or array -> int to single | array to multiple outout
     * @param $field string of field name
    **/
    static function formatTokenInput($model, $id, $field='name', $formatter=null)
    { 
        if( $id )
        {
            if( $formatter )
                $model->select('*');
            else
                $model->select('id', $field.' as name');

            if( is_array($id) && $id )
            {
                $rows = $model->whereIn('id', $id)->get();
            }
            else
            {
                $rows = $model->find($id);

                $rows = $rows ? [$rows] : '';
            }

            if( $formatter )
            {
                foreach( $rows as $k=>$v )
                {
                    $v['name'] = self::{$formatter}($v);
                    $rows[$k] = $v;
                }
            }

            return json_encode($rows);
        }
    }

    /** 
     * GET LOCATION
    **/
    static function getLocation($type=['provinsi'], $parent=0)
    {
        $ret = [];

        \App\Models\Location::whereIn('type', $type)->chunk(500, function($rows) use(&$ret) {
            $ret =  array_merge($ret, $rows->pluck('name', 'master_id')->toArray());
        });

        return $ret;
    }

    /** 
     * SAVE FREE TAGING 
    **/
    static function freeTag($t, $type='REGULAR')
    {
        if( substr($t, 0, strlen(config('tags.free_tag_prefix'))) == config('tags.free_tag_prefix') )
        {
            $tag = str_replace(config('tags.free_tag_prefix'), '', $t);

            $dtInsert = [
                'title'=> ucwords($tag),
                'slug'=> \Str::slug($tag),
                'type'=> $type,
                'created_at'=> date("Y-m-d H:i:s"),
                'created_by'=> \Auth::check() ? \Auth::user()->id : null
            ];

            $t = \App\Models\Tag::insertGetId($dtInsert);
        }

        return $t;
    }

    static function messageUser($r)
    {
        //dd($r);

        $data = [
            'from'=> [
                'name'=> 'unkown',
                'image'=> asset('static/png/no-image'),
            ],
            'to'=> [
                'name'=> 'unkown',
                'image'=> asset('static/png/no-image'),
            ]
        ];

        //from
        if( $c = \App\User::find($r->from_id) )
        {
            $data['from'] = [
                'name'=> $c->name.' <'.$c->email.'>',
                'image'=> $c->photo ? self::imgSrc($c->photo) : asset('static/png/no-image.png'),
            ];
        }

        //to
        if( $c = \App\User::find($r->to_id) )
        {
            $data['to'] = [
                'name'=> $c->name.' <'.$c->email.'>',
                'image'=> $c->photo ? self::imgSrc($c->photo) : asset('static/png/no-image.png'),
            ];
        }

        return $data;
    }

    static function deleteAutoSave($page=null)
    {
        $page = $page ?? self::page();
        
        $fileAutoSave = storage_path('autosave/'.$page.'_'.\Helper::userId().'.json');
        
        if( file_exists($fileAutoSave) )
        {
            \Session::forget('_old_input');
            \Session::forget('autosaved_state');

            unlink($fileAutoSave);
        }
    }

    /** 
     * SAVE FREE TAGING PENDANAAN / REKANAN
    **/
    static function lookupID($model, $t, $field='nama', $additional=null)
    {
        if( substr($t, 0, strlen(config('tags.free_tag_prefix'))) == config('tags.free_tag_prefix') )
        {
            $tag = str_replace(config('tags.free_tag_prefix'), '', $t);

            if( !trim($tag) ) return null;

            $dataInsert = [
                $field=> $tag,
                'created_at'=> date("Y-m-d H:i:s"),
                'created_by'=> \Auth::check() ? \Auth::user()->id : null
            ];

            if( $additional ) $dataInsert+= $additional;

            $t = $model::insertGetId($dataInsert);
        }

        return $t;
    }
    
    static function createSlug($model, String $slug, String $field = 'slug')
    {
        $_slug = \Str::slug($slug);
        $next = 1;

      // Loop until we can query for the slug and it returns false
      while ( $model::where($field, $_slug)->first() )
      {
          $_slug = \Str::slug($slug .' '. $next);
          $next++;
      }
    
      return $_slug;
    }

    static function createTransaction($userId)
    {
        return \App\Models\Transaction::create([
            'invoice' => 'INV-'. time() .'-'. mt_rand(100, 999),
            'date' => self::formatDate(null),
            'status' => '0', // pending
            'user_id' => $userId,
            'created_at' => self::formatDate(null),
        ]);
    }
}