<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PhotosController extends Controller
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

//        $l = fopen('/home/sudipta/log.log','a+');
  //      fwrite($l,"Edit PID :".$request->p_id." \n");
        
        //echo $request->p_id;
        //Check if File has been passed
        $hasfile = 1;
        //For Update and Create
        if($request->p_id && $request->p_id>0){
            $photo = Photo::find($request->p_id);
            $hasfile = $request->file('albumphoto') == ''?0:1;
        }else {
            $photo = new Photo();
        }
        //Assign file Only if its been posted
        if($hasfile){
            //$f = sizeof($_FILES);
            $destination_path = 'uploads/';
            $file = $request->file('albumphoto');
            $filename = str_random(6) . '_' . $request->file('albumphoto')->getClientOriginalName();
            $file->move($destination_path, $filename);
            $photo->photopath = $destination_path . $filename;
            //var_dump($file);            
    //        fwrite($l,"File :".$filename." Name: ".$request->title."active ".$request->active);            
        }
        $name = $request->title;

        //Setting Enabled Photo Check in DB
        $active = 0;
        if($request->active == 'on'){
            $active = 1;
        }
        //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        //$filename = "PHOTO";

        $photo->title = $request->title;
        $photo->description = $request->description;
        $photo->source = $request->source;
        $photo->source_url = $request->sourceurl;
        $photo->channel_id = $request->channel_id;
        $photo->owned_by = $request->owner;
        $photo->active = $active;
        //$author->photo = $request->photo;
        $photo->valid = '1';

        $photo->save();
        //fwrite($l,"\n PID being passed :".$photo->photo_id." \n");
        $ppass = $photo->photo_id;
      //  fclose($l);

        return $ppass;

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
    public function destroy(Request $request)
    {

        //Delete passed Id's row from DB - Deprecated to Invalidate Row
        $id = $request->photoId;
        //$l = fopen('/home/sudipta/log.log','a+');
        //fwrite($l," ID  :".$id);
        DB::table('photos')->where('photo_id',$id)
            ->update(['valid' => 0]);
        //$photo = new Photo();
        //$photo->destroy($id);

    }
}
