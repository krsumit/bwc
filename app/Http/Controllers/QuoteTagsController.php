<?php

namespace App\Http\Controllers;

use App\QuoteTag;
use Illuminate\Http\Request;

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
    public function store()
    {
        //Save passed string in Table - Validate First

        $count = 0;
        if (isset($_GET['option'])) {
            $tagString = $_GET['option'];
        }
//        $l = fopen('/home/sudipta/log.log', 'a+');
        //$tagString = $request->tag;
        $arrTags = explode(',', $tagString);
        $count = sizeof($arrTags);

        //For Response
        $returnTag1 = new QuoteTag();
        $returnArr = new QuoteTag();
        $returnTag = new QuoteTag();

        //For more than 1 Tag Added (Comma separated list)
        if($count >= 1) {
            if ($count > 1) {
                for ($i = 0; $i < $count; $i++) {
                    $tag = new QuoteTag;
                    $tag->tag = trim($arrTags[$i]);
                    $tag->save();
                    $cond = $tag->tag_id;
  //                  fwrite($l,"\ni:".$i." File :".$cond." ID: ".$tag->tag_id);
                    $returnArr[$i] = $returnTag->all()->where('tag_id',"$cond");
                }
                $returnTag1 = $returnArr;
            } else if ($count == 1) {
                $tag = new QuoteTag;
                $tag->tag = trim($tagString);
                $tag->save();
    //            fwrite($l," ID ADDED :".$tag->tag_id);
                $cond = $tag->tag_id;
                //$returnTag1 = $returnTag->all()->where(['tags_id'=>"$cond",'tags_id'=>'2']);
                $returnTag1 = $returnTag->all()->where('tag_id',"$cond");
            }
            //fwrite($l,"File :".$returnTag->all()->get('1'));
        }
      //  fclose($l);
        return $returnTag1;
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
