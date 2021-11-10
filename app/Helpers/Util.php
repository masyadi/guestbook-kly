<?php
namespace App\Helpers;

Trait Util {

	/**
	* condition value 
	* ex : val($m, 'name')
	* ex : val($m, 'info.version') subkey separated by dot
	**/
	static function val($row, $key, $default='')
	{
	    //sub key
	    if ( strpos($key, '.') )
	    {
	        $arr = explode('.', $key);

	        for( $i=0; $i<count($arr)-1;$i++ )
	        {
	            $row = self::val($row, $arr[$i], $default);   
	        }
	        
	        return self::val($row, end($arr), $default);
	    }
	    else
	    {
	    	if( is_array($row) )
	    	{
		        return isset($row[$key]) 
			        && $row[$key] 
			        && $row[$key]!='0000-00-00' 
			        && $row[$key]!='0000-00-00 00:00:00' 
			        && $row[$key]!='1970-01-01' 
			        && $row[$key]!= '' ? $row[$key] : $default; 
			}
			else
			{
				return isset($row->{$key}) && $row->{$key} ? $row->{$key} : $default;
			}
	    }
	}

	/**
	* Email MX check
	* @param $email string email address
	* @param $host array email host
	**/
	static function mxEmail($email, $host=['google.com'])
	{
		$ret = false;

		$arr = explode('@', $email);

		if( isset($arr[1]) && $arr[1])
		{
			$domain = $arr[1];

        	$mx = dns_get_record($domain, DNS_MX);

        	if( $mx )
        	{
        		foreach( $mx as $m )
        		{
        			foreach( $host as $h )
        			{
	        			if( preg_match('/'.$h.'$/', $m['target']) )
	        			{
	        				$ret = true;
	        			}
	        		}
        		}
        	}
        
		}

		return $ret;
	}

	static function image_ratio($w, $h)
	{
	    $gcd = function($w, $h) use (&$gcd) {
	        return ($w % $h) ? $gcd($h, $w % $h) : $h;
	    };
	    $g = $gcd($w, $h);

	    return $w/$g . ':' . $h/$g;
	}

	static function getGps($exifCoord, $hemi)
	{
		$degrees = count($exifCoord) > 0 ? self::gps2Num($exifCoord[0]) : 0;
	    $minutes = count($exifCoord) > 1 ? self::gps2Num($exifCoord[1]) : 0;
	    $seconds = count($exifCoord) > 2 ? self::gps2Num($exifCoord[2]) : 0;

	    $flip = ($hemi == 'W' or $hemi == 'S') ? -1 : 1;

	    return $flip * ($degrees + $minutes / 60 + $seconds / 3600);
	}

	static function gps2Num($coordPart) {

	    $parts = explode('/', $coordPart);

	    if (count($parts) <= 0)
	        return 0;

	    if (count($parts) == 1)
	        return $parts[0];

	    return floatval($parts[0]) / floatval($parts[1]);
	}

	/*
     |--------------------------------------------------------------------------
     | GET ROW ARRAY
     |--------------------------------------------------------------------------
     */
    static function getRowArray($rows, $key='id', $value='*', $subKey='')
    {
        $ret = [];

        $isArray = is_array($rows) ? true : false;

        if( $rows )
        {
            foreach($rows as $k=>$r)
            {
                if( $isArray )
                {
                    if( is_array($key) )
                    {
                        $ret[$r[$key[0]]][ ( $subKey ? ( isset($r[$subKey]) ? $r[$subKey] : $k ) : $k ) ] = ($value=='*') ? $r : val($r, $value);
                    }
                    else
                    {
                        $ret[$r[$key]] = $value=='*' ? $r : $r[$value];
                    }
                }
                else
                {
                    if( is_array($key) )
                    {
                        $ret[$r->$key[0]][ ( $subKey ? ( isset($r->$subKey) ? $r->$subKey : $k ) : $k ) ] = ($value=='*') ? $r : val($r, $value);
                    }
                    else
                    {
                        if(isset($r->$key)) $ret[$r->$key] = $value=='*' ? $r : $r->$value;
                    }
                }
                
            }
        }

        return $ret;
    }

	/*
     |--------------------------------------------------------------------------
     | CONVERT BASE 64 to URL
     |--------------------------------------------------------------------------
     */
	static function convertBase64toUrl(&$request)
    {
        if( $request )
        {
            foreach( $request as $k=>$v )
            {
                preg_match_all('<img.*?src=[\'"]data\:image/(.*?)[\'"].*?>', $v, $images, PREG_SET_ORDER);

                if( $images )
                {
                    foreach( $images as $img )
                    {
                        if( isset($img[1]) )
                        {
                            $data = 'data:image/'.$img[1];
                            $dataImage = $data;

                            list($type, $dataImage) = explode(';', $dataImage);
                            list(,$extension)       = explode('/',$type);
                            list(,$dataImage)       = explode(',', $dataImage);
                            $fileName = uniqid().'.'.$extension;
                            $dataImage = base64_decode($dataImage);

                            $subfolder = self::page().date('/Y/m/d');

                            $path = \Storage::disk()->put($subfolder.'/'.$fileName, $dataImage);

                            $request[$k] = str_replace($data, \Storage::url($subfolder.'/'.$fileName), $v);
                        }
                    }
                }
            }
        }

        return $request;
    }
}