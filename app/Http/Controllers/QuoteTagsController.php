<?php

namespace App\Http\Controllers;

use App\QuoteTag;
use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class QuoteTagsController extends Controller
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
        //Save passed string in Table - Validate First
        $count = 0;
        $tagString = $request->tag;
        $arrTags = explode(',', $tagString);
        $count = sizeof($arrTags);
        $returnTag1 = new QuoteTag();
        $returnArr = array();//new Tag();
        $returnTag = new QuoteTag();
       
        if($count >= 1) {
            if ($count > 1) {
                for ($i = 0; $i < $count; $i++) {
                    $tag = new QuoteTag;
                    $tag->tag = trim($arrTags[$i]);
                    if($tagrow=$returnTag->where('tag',trim($arrTags[$i]))->select('tag_id as id', 'tag as name')->first()){
                        $returnArr[]=$tagrow;
                    }
                    
                    else{
                         if(trim($arrTags[$i])){
                        $tag->save();
                        //print_r($tag);exit;
                        $returnArr[]=array('id'=>$tag->tag_id,'name'=>$tag->tag);
                         }
                    }
                   
                }
                $returnTag1 = $returnArr;
            } else if ($count == 1) {
                // $l = fopen('/home/sudipta/check.log', 'a+');
                $tag = new QuoteTag;
                $tag->tag = trim($tagString);
                if($tagrow=$returnTag->where('tag',trim($tagString))->select('tag_id as id', 'tag as name')->first()){
                        $returnArr[]=$tagrow;
                    }else{
                          $tag->save();
                           //print_r($tag);exit;
                          $returnArr[]=array('id'=>$tag->tag_id,'name'=>$tag->tag);
                       
                    }
              
             
            }
          
        }
        return $returnArr;

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
    
    public function returnJson() {
        //DB::enableQueryLog();
        $matchText = $_GET['q'];
        $tag = new QuoteTag;
        //->all()
        $rst = $tag->where('tag', "like", $matchText . '%')->select('tag_id as id', 'tag as name')->get();
          return response()->json($rst);
    }
    
    public function returnauthorJson() {
        //DB::enableQueryLog();
        $matchText = $_GET['q'];
       
        //->all()
        $rst = DB::table('quotescategory')->where('category', "like", $matchText . '%')->select('cate_id as id', 'category as name')->get();
          return response()->json($rst);
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
