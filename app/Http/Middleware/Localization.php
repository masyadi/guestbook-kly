<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $localeDirs = $this->getDirectories(__DIR__ .'/../../../resources/lang/');

        if( $request->is('api/*') )
        {
            // API request
            if( $request->hasHeader('x-locale') )
            {
                $locale = $request->header('x-locale');

                if( in_array($locale, $localeDirs) )
                {
                    App::setLocale($locale);
                }
            }
        }
        else if( $request->is(config('app.cms_path') .'/*') )
        {
            // CMS request
        }
        else
        {
            // WEB request
            if( session()->has('selected_locale') )
            {
                App::setLocale(session()->get('selected_locale'));
            }
        }

        return $next($request);
    }

    private function getDirectories(string $path) : array
    {
        $directories = [];
        $items = scandir($path);
        foreach ($items as $item) {
            if($item == '..' || $item == '.')
                continue;
            if(is_dir($path.'/'.$item))
                $directories[] = $item;
        }
        return $directories;
    }
}
