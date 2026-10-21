<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class shortURLController extends Controller
{


    /**
     * shortURLController constructor.
     * @param string $key
     * @param string $apiURL
     */






    /**
     * shortURLController constructor.
     */




    // Shorten a URL

    public static function shorten($url, $custom = "", $format = "json") {
        $api_url = "http://felit.ir/api/?key=u6UgKDxrHtPk";
        $api_url .= "&url=".urlencode(filter_var($url, FILTER_SANITIZE_URL));
        if(!empty($custom)){
            $api_url .= "&custom=".strip_tags($custom);
        }
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => $api_url
        ));
        curl_setopt($curl, CURLOPT_TIMEOUT, env('EXCEPTION_TIME', 15)); //timeout in seconds

        $Response = curl_exec($curl);
        curl_close($curl);
        if($format == "text"){
            $Ar = json_decode($Response,TRUE);
            if($Ar["error"]){
                return $Ar["msg"];
            }else{
                return $Ar["short"];
            }
        }else{
            return $Response;
        }
    }

    public static function ShortLinkMaker($url, $custom = "", $format = "json")
    {
        return $url;
        try {
            $ink = self::shorten($url, $custom, $format);
            $reult = json_decode($ink);
            if ($reult->error == 0) {
                return substr_replace($reult->short, 'www.', 0, 7);
            }
        }  catch (\Exception $e) {
            return $url;
        }

    }


  
    //
}

