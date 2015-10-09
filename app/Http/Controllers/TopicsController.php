<?php

namespace App\Http\Controllers;

use App\Topic;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Generate matching Topics from the passed Content
     *
     * @returns Array of topics
     */
    public function generate(Request $request){

        $content = $request->detail;
        $arrTopics = Topic::where('valid','=',1)->get();
        //$asd = fopen("/home/sudipta/log.log", 'a+');


        $mcount = 0;
        $matches = array();
        $subject = "abdef";
        $pattern = '/def/';
        //fwrite($asd, " Content ::" . $content ."\n");
        $matched = array();
        $mc = 0;
        foreach($arrTopics as $topic=>$v){
            $pattern = '/\b('.$v->topic.')\b/';
            $ret = preg_match($pattern.'i', $content, $matches[$mcount], PREG_OFFSET_CAPTURE);
            //preg_match_all($pattern, $content, $matches, PREG_SET_ORDER);
            //fwrite($asd, " Topic Loop ::" . $mcount . " topic :" . $pattern . " matched ".$ret." and ".count($matches[$mcount])." \n");
            if($ret == 1){
                $matched[$mc]['id'] = $v->id;
                $matched[$mc]['topic'] = $v->topic;
                $mc++;
            }
            $mcount++;
        }

        //fwrite($asd, " Out ::" . count($matched) . " \n");

        //fclose($asd);
        return $matched;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
