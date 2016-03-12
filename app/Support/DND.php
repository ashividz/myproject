<?php

namespace App;

use Illuminate\Http\Request;

use App\Http\Requests;

/*define("TIMEOUT", 2000);

#DNCScrubStatusConstants
define("VALUE_OK"       , "OK");
define("VALUE_NOT_OK"   , "NOT_OK");


#some constants for dnc url
define("DNC_SCRUB_URL"      , "www.dncindia.com/dacx/jsonCommand");
define("PARAMETER_API"      , "command");
define("SINGLE_SCRUB_API"   , "singleScrubApi");
define("MULTI_SCRUB_API"    , "multiScrubApi");
define("PARAMETER_DATA"     , "data");
define("API_KEY_FIELD"      , "apiKey");
define("NUMBER_FIELD"       , "number");
define("CATEGORY_FIELD"     , "categories");
define("HTTPS"              , "https://");
define("HTTP"               , "http://");

#DNCScrubCategoryConstants
define("DNCINDIA_STATUS"        , "status");
define("DNCINDIA_NUMBER"        , "number");
define("DNCINDIA_ERROR_REASON"  , "errorReason");
define("DNCINDIA_ERROR_CODE"    , "errorCode");
define("DNCINDIA_BEANS"         , "beans");
define("DNCINDIA_CALL"          , "call");
define("DNCINDIA_CBEI"          , "CBEI");
define("DNCINDIA_BIFC"          , "BIFC");
define("DNCINDIA_REAL_ESTATE"   , "realEstate");
define("DNCINDIA_EDUCATION"     , "education");
define("DNCINDIA_HEALTH"        , "health");
define("DNCINDIA_CG_AND_A"      , "");
define("DNCINDIA_TANDL"         , "TandL");

#INVALID json error code
define("INVALID_JSON_ERROR_CODE"    ,   -1);
define("INVALID_JSON_ERROR_DESC"    ,   "Error in json parsing");*/

define("BASE_URI"                    ,  "http://checkdnd.com/api/check_dnd_no_api.php");

class DND
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function scrub($mobile)
    {
        if ($mobile == NULL) {
            return 'null';
        }

        $client = new \GuzzleHttp\Client();

        $response = $client->get(BASE_URI."?mobiles=".$mobile);

        $body = $response->getBody();

        $status = substr($body, strlen($body)-4, 1);
        
        if($status=='Y'){

            return true;

        } else if ($status=='N') {

            return false;
        }
        
        return 'error';
    }

    /*

    public function dncSingleScrubStatus($phoneNumber, $apiKey, $categories, $ishttps)
    {
    if($phoneNumber == NULL){
        $phoneNumber='null';
    }
    #Returing the Scrub Status
    return  $this->dncScrubStatus(SINGLE_SCRUB_API, $apiKey, $phoneNumber, $categories, $ishttps);
    }


    function stringArraysToJson($apiKey, $phoneNumbers, $categories)
    {
        $query=array();
        
        if($categories == NULL){
            $categories = "";
        }
        
        if($phoneNumbers !== NULL)
        {
            $query[NUMBER_FIELD]    =   $phoneNumbers;
        }
        $query[API_KEY_FIELD]   =   $apiKey;
        $query[CATEGORY_FIELD]  =   $categories;

        $jsonEncodedURLquery    =   json_encode($query);
        //echo $jsonEncodedURLquery;
        return $jsonEncodedURLquery;
    }

    function dncScrubStatus($api, $apiKey, $phoneNumbers, $categories, $ishttps)
    {
    #Making a curl handle to make a http request through it
    $curl_handle = curl_init();

    /*
     * Checking if request is https then set a option in curl that make it works as a https request
     */
    
    /*if ($ishttps == TRUE) 
    {
        $url = HTTPS.DNC_SCRUB_URL;
                
        //WARNING: this would prevent curl from detecting a 'man in the middle' attack
        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    } 
    else 
    {
        $url = HTTP.DNC_SCRUB_URL;
    }
    
    $query =    http_build_query(array(PARAMETER_API=>$api,PARAMETER_DATA=>$this->stringArraysToJson($apiKey, $phoneNumbers, $categories)));
    
    #Setting the curl handle parameters such as url , timeout
    curl_setopt($curl_handle,   CURLOPT_URL,            $url);
    curl_setopt($curl_handle,   CURLOPT_POST,           TRUE);
    curl_setopt($curl_handle,   CURLOPT_POSTFIELDS,     $query);
    curl_setopt($curl_handle,   CURLOPT_TIMEOUT,        TIMEOUT);
    curl_setopt($curl_handle,   CURLOPT_RETURNTRANSFER, TRUE);

    
    #Actually making the request to DNCScrub API and storing the result
    $result = curl_exec($curl_handle);


    if (!empty($result))
    {
        #Converting the json String into an associative array
        $result = json_decode($result, TRUE);
        
        #if there is some error in json parsing due to invalid json
        if(empty($result))
        {
            $result[DNCINDIA_STATUS]        =   VALUE_NOT_OK;
            $result[DNCINDIA_ERROR_REASON]  =   INVALID_JSON_ERROR_DESC;
            $result[DNCINDIA_ERROR_CODE]    =   INVALID_JSON_ERROR_CODE;
        }
    }
    else
    {
        $result[DNCINDIA_STATUS]        =   VALUE_NOT_OK;
        $result[DNCINDIA_ERROR_REASON]  =   curl_error($curl_handle);
        $result[DNCINDIA_ERROR_CODE]    =   curl_errno($curl_handle);
    }
    

    #Closing the handle
    curl_close($curl_handle);

    #sending the parsed json response back as a Associative array
    return $result;
    }*/


    
}
