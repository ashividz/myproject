<?php

namespace App\Support;

use DateTime;
use Auth;

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

		if(strlen($mobile) >= '12' && substr($mobile, 0, 2) == '91')
		{
   		 	$mobile = substr($mobile, 2, strlen($mobile));
		}

		$mobile = ltrim($mobile, '0');

		return $mobile;
	}

	public static function validateDate($date)
	{
	    $d1 = DateTime::createFromFormat('Y-m-d', $date);
	    $d2 = DateTime::createFromFormat('Y/m/d', $date);

	    if ($d1) {
	    	if ($d1->format('Y-m-d') == $date) {
	    		return true;
	    	}
	    }
	    if ($d2) {
	    	if ($d2->format('Y/m/d') == $date) {
	    		return true;
	    	}
	    }

	    return false;
	}

	public static function nl2p($string)
	{
	    $paragraphs = '';

	    foreach (explode("\n", $string) as $line) {
	        if (trim($line)) {
	            $paragraphs .= '<p>' . $line . '</p>';
	        }
	    }

	    return $paragraphs;
	}

	/* Multi Array Sort by Column*/

	public static function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
	    $sort_col = array();
	    foreach ($arr as $key=>$row) {
	        $sort_col[$key] = $row[$col];
	    }

	    return array_multisort($sort_col, $dir, $arr);
	}
}