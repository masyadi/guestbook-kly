<?php
namespace App\Helpers;

Trait Url {

	static function imgSrc($path, $size = null)
	{
		return $path ? \Storage::url(($size?$size.'/':'').$path) : asset('static/png/no-image.png');
	}

	static function CMS($path)
	{
		return url(trim(config('app.cms_path').'/'.$path, '/'));
	}

	static function url_encode($array)
	{
		return base64_encode(urlencode(json_encode($array)));
	}

	static function url_decode($string)
	{
		return json_decode(urldecode(base64_decode($string)), true);
	}

	static function page($url=null)
	{
		$current = $url ? $url : url()->current();
		$current = str_replace(config('app.url'), '', $current);
		$current = str_replace(config('app.path'), '', $current);
		$current = str_replace(config('app.cms_path'), '', $current);
        $current = trim($current, '/');
        $current = explode('/', $current)[0];
        $current = str_replace('http:', '/', $current);

		return $current;
	}

	static function detail($row, $isPreview=false)
	{
		return url(($row->relcategory->slug??($row['relcategory']['slug']??'')).'/'.($row->slug??($row['slug']??'')).'.html').($isPreview ? '?preview='. md5(($row->id??($row['id']??null)).'-'.($row->created_at??($row['created_at']??null))) :'');
	}

	static function activeMenu($uri = '')
	{
		$active = null;
		if( \Request::is($uri) || \Request::is($uri .'/*') || \Request::is(\Request::segment(1) . '/' . $uri . '/*') || \Request::is(\Request::segment(1) . '/' . $uri) )
		{
			$active = 'active';
		}
		return $active;
	}
}