<?php
namespace App\Helpers;

Trait Date {

	static function formatDate($date, $format='Y-m-d H:i:s')
	{
		if( !$date ) return date("Y-m-d H:i:s");

		if( $format=='id' ) $format = 'd M Y H:i';
		if( $format=='tgl_id' ) $format = 'd M Y';

		return date($format, strtotime($date));
	}

	/** 
	 * PARSE DATE RANGE
	 * ex: {"begin":"1-2010","end":"4-2019"}
	**/
	static function parseDateRange($date, $resultString=true)
	{
		$date = json_decode($date);

		if( !$date ) return $resultString ? '' : [];

        $dateFormat = new \Helper;

        $result = [
        	'begin'=> $dateFormat->dateLocale('01-'. $date->begin, '%B %Y'),
        	'end'=> $dateFormat->dateLocale('01-'. $date->end, '%B %Y'),
        ];

        return $resultString ? $result['begin'].' - '.$result['end'] : $result;
	}

}