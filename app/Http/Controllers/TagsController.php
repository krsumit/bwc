<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Tag;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    
    public function __construct() {
        $this->middleware('auth');
    }  
    
    
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
        //Save Request Tuple in Table - Validate First

        $count = 0;
        $tagString = $request->tag;
        $arrTags = explode(',', $tagString);
        $count = sizeof($arrTags);

        //For Response
        $returnTag1 = new Tag();
        $returnArr = array();//new Tag();
        $returnTag = new Tag();
        //For more than 1 Tag Added (Comma separated list)
        if($count >= 1) {
            if ($count > 1) {
                for ($i = 0; $i < $count; $i++) {
                    $tag = new Tag;
                    $tag->tag = trim($arrTags[$i]);
                    if($tagrow=$returnTag->where('tag',trim($arrTags[$i]))->select('tags_id as id', 'tag as name')->first()){
                        $returnArr[]=$tagrow;
                    }
                    
                    else{
                         if(trim($arrTags[$i])){
                        $tag->save();
                        $returnArr[]=array('id'=>$tag->tags_id,'name'=>$tag->tag);
                         }
                    }
                   
                }
                $returnTag1 = $returnArr;
            } else if ($count == 1) {
                // $l = fopen('/home/sudipta/check.log', 'a+');
                $tag = new Tag;
                $tag->tag = trim($tagString);
                if($tagrow=$returnTag->where('tag',trim($tagString))->select('tags_id as id', 'tag as name')->first()){
                        $returnArr[]=$tagrow;
                    }else{
                          $tag->save();
                          $returnArr[]=array('id'=>$tag->tags_id,'name'=>$tag->tag);
                       
                    }
               // if($returnTag->where('tag',trim($tagString))->count() == 0)
                   
              // $returnTag1 = $returnTag->all()->where('tags_id',"$cond");
             
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
        $tag = new Tag;
        //->all()
        $rst = $tag->where('tag', "like", $matchText . '%')->select('tags_id as id', 'tag as name')->get();
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
