<?php
namespace App\Http\Middleware;

use Closure, Auth, Helper, Session, Config;

class Authed {

	protected $_exception = ['login', 'logout', 'accept-invitation'];

    public function handle($request, Closure $next)
    {
    	if( !Auth::guard('cms')->check() && !in_array(Helper::page(), $this->_exception))
        {
        	return redirect(Helper::CMS('login?_c='.Helper::url_encode([
                '_type' => 'cms',
        		'_next' => url()->full()
        	])));
        }
        
        if( Auth::guard('cms')->check() && Helper::page() == 'login' )
        {
            return redirect(Helper::CMS(''));
        }

        //CURRENT MENU
        if( $p = Session::get('menu.slug_id.'.Helper::page()) )
        {
            if($menu = Session::get('menu.data.'.$p))
            {
                Config::set('current_menu', $menu);
                
                Config::set('breadcrumb', array_reverse($this->_getBreadCrumb($p)));
            }
        }
        
        //AUTO SAVE
        if( Auth::guard('cms')->check() )
        {
            $fileAutoSave = storage_path('autosave/'.Helper::page().'_'.Auth::guard('cms')->user()->id.'.json');

            $action = Helper::val(explode('@',\Route::currentRouteAction()), 1);

            Session::forget('_old_input');

            if( file_exists($fileAutoSave) && $action=='create' )
            {
                $row = json_decode(file_get_contents($fileAutoSave), true);

                Session::put('autosaved_state', 1);

                foreach( $row as $k=>$v )
                {
                    Session::put('_old_input.'.$k, $v);
                }
            }
        }

        return $next($request); 
    }
    
    /** BUILD BREADCRUM **/
    private function _getBreadCrumb($id)
    {
        $menu = Session::get('menu.data.'.$id);
        
        $row = [];
        
        if( $menu )
        {
            $row[] = [
                'url'=> $menu['slug'] ?? Helper::CMS($menu['slug']),
                'icon'=> $menu['icon'],
                'title'=> $menu['name'],
            ];
            
            if( $menu['parent'] )
            {
                $row = array_merge($row, $this->_getBreadCrumb($menu['parent']));
            }
            elseif( $menu['slug']!='dashboard' )
            {
                $row[] = [
                    'url'=> Helper::CMS(''),
                    'icon'=> 'fa fa-home',
                    'title'=> __('Dashboard'),
                ];
            }
        }
            
        return $row;
    }
}