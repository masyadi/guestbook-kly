<?php
namespace App\Helpers;

Trait Number {

	static function formatNumber($number, $decimalNum=0, $decimal='.', $thousand=',')
	{
		if( !$number ) return 0;

		return number_format($number, $decimalNum, $decimal, $thousand);
	}

	static function formatBytes($size, $precision = 2) 
	{ 
	    $base = log($size, 1024);
	    $suffixes = array('', 'K', 'M', 'G', 'T');   

	    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)]; 
	} 

}