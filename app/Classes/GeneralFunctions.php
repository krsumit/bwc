<?php

namespace App\Classes;
use Illuminate\Support\Facades\DB;

class GeneralFunctions {

    public static function clearFileName($name) {
        return $name;
    }
    
    public static function sendWhatsappBroadcast($data){
//        $key='7c5e0350c00a96852f2013ca014c71a4_9580_05720d08eb0689adc71fea0f6a';
//        $url='https://rest.whatsbroadcast.com/api/v1/newsletter';
//        $data['apikey']=$key;
//        $postfilelds=http_build_query($data);
//        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL,$url);
//        curl_setopt($ch, CURLOPT_POST, 1);
//        curl_setopt($ch, CURLOPT_POSTFIELDS,$postfilelds);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        $server_output = curl_exec ($ch);
//        curl_close ($ch);
//        return $server_output;
//        $dataArray=json_decode($server_output);
//        if(trim($dataArray->code)=='200'){
//            $up_query="update quotes set whatsaap_bd=1 where quote_id=".$quotestRow['quote_id'];
//            DB::update($up_query);
//        }
    }
    
}
