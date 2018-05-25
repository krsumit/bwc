<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;

class Helper
{
 public static function rscUrl($string) {
        //Lower case everything
        $string = strtolower($string);
        //Make alphanumeric (removes all other characters)
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        //Clean up multiple dashes or whitespaces
        $string = preg_replace("/[\s-]+/", " ", $string);
        //Convert whitespaces and underscore to dash
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }

public static function is_url_exist($url){
            $ch = curl_init($url);    
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_exec($ch);
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if($code == 200){
               $status = true;
            }else{
              $status = false;
            }
            curl_close($ch);
           return $status;
        }
 public static function cleanFileName($fileName)
    {
     $maxLen=100;
     $arr=explode('.',$fileName);
     $extension=array_pop($arr);
     $fName=implode('_',$arr);
     $fName=preg_replace('{([^a-zA-Z0-9_])+}', '_',$fName);
     $fName=preg_replace('/__+/', '_', $fName);
     $fName=substr($fName,0,$maxLen);
     $fName=$fName.'.'.$extension;
     return $fName;
    }
}

