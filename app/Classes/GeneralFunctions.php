<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;

class GeneralFunctions {

    public static function cleanFileName($name) {
       // echo $name; exit;
        $pos=strrpos($name,'.');
        $fileName=substr($name,0,$pos);
        $extension=substr($name,$pos+1);
        $fileName=preg_replace('/([^a-zA-Z0-9]){1,}/', '_',$fileName);
        return str_random(6).'_'.$fileName.'.'.$extension;
    }
    
    public static function sendWhatsappBroadcast($data){  
        
        $key='7c5e0350c00a96852f2013ca014c71a4_9580_05720d08eb0689adc71fea0f6a';
        $url='https://rest.whatsbroadcast.com/api/v1/newsletter';
        $data['apikey']=$key;
        $data['date']=date('Y-m-d-H-i',time()-12000);  // Gmt+3 Germany-time zone, In indian time zone it's 10 min after saving action
        //$data['targeting_id']='3405';
        $postfilelds=http_build_query($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfilelds);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec ($ch);
        curl_close ($ch);
        return $server_output;
//        $dataArray=json_decode($server_output);
//        print_r($dataArray); exit;
//        if(trim($dataArray->code)=='200'){
//            $up_query="update quotes set whatsaap_bd=1 where quote_id=".$quotestRow['quote_id'];
//            DB::update($up_query);
//        }
        
    }
    
    
}
