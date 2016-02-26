<?php

namespace App\Support;

class Helper {
	public static function emptyStringToNull($string)
    {
        //trim every value
        $string = trim($string);

        if ($string === ''){
           return null;
        }

        return $string;
    }

    public static function properCase($string) 
    {
	   	$words = explode(" ", $string);
	   	$result = NULL;
		for ($i=0; $i<count($words); $i++) {
			$s = strtolower($words[$i]);
			$s = substr_replace($s, strtoupper(substr($s, 0, 1)), 0, 1);
			$result .= " " . $s;
		}
		return trim($result);
	}

	public static function properMobile($mobile)
	{
		$mobile = preg_replace('/[^\dxX]/', '', $mobile);
		$mobile = ltrim($mobile, '0');

		if(substr($mobile, 0, 2) == '91')
		{
   		 	$mobile = substr($mobile, 2, strlen($mobile));
		}

		$mobile = ltrim($mobile, '0');

		return $mobile;
	}
}