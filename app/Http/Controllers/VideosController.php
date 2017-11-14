<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class VideosController extends Controller
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
        //Save Request Tuple in Table - Validate First

        // Validation //
        //$validation = Validator::make($request->all(), [
        //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
        //'description' => 'required',
        //  'albumphoto'     => 'required|image|mimes:jpeg,png|min:1|max:250'
        //]);

        //For Add or Update
        //$l = fopen('/home/sudipta/log.log','a+');        
        //fwrite($l," Video ID: ".$request->v_id);

        if(($request->v_id) &&($request->v_id != '')){
            $video = Video::find($request->v_id);
        }else{
            $video = new Video();
        }

        $name = $request->title;
        //var_dump($file);
        //fwrite($l," Name: ".$name);
//fclose($l);
        $video->title = $request->title;
        $video->code = $request->code;
        $video->source = $request->source;
        $video->url = $request->url;
        $video->channel_id = $request->channel_id;
        $video->owned_by = $request->owner;
        $video->added_by = '';
        $video->added_on = '';

        $video->valid = '1';

        $video->save();

        return $video->video_id;

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
