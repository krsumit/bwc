<?php

namespace App\Http\Controllers;

use App\Photo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
use App\Classes\FileTransfer;
class PhotosController extends Controller
{
    
    
    
    
    public function __construct() {
        $this->middleware('auth');
    
    }
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
        $photo=Photo::find($request->photoId);
        $fileTran=new FileTransfer();
        switch ($photo->owned_by):
        case 'article':
           // echo config('constants.awbucket').config('constants.awarticleimagethumbtdir').$photo->photopath;exit;
            $updatearray = array('updated_at' => date('Y:m:d H:i:s'));
            $updatearray['social_image']='';
           
            // print_r($updatearray); exit;
            //if(trim($photo->photopath)){
             DB::table('articles')
                    ->where('social_image', $photo->photopath)
                     ->where('article_id',$photo->owner_id)
                    ->update($updatearray);
            //}
            
            if(count(Photo::where('photopath','=',$photo->photopath)->get())==1){
                $fileTran->deleteFile(config('constants.awarticleimagethumbtdir'), $photo->photopath);
                $fileTran->deleteFile(config('constants.awarticleimagemediumdir'), $photo->photopath);
                $fileTran->deleteFile(config('constants.awarticleimagelargedir'), $photo->photopath);
                $fileTran->deleteFile(config('constants.awarticleimageextralargedir'), $photo->photopath);
          }
           break;
        case 'quickbyte':
             $fileTran->deleteFile(config('constants.awquickbytesimagethumbtdir'), $photo->photopath);
             $fileTran->deleteFile(config('constants.awquickbytesimagemediumdir'), $photo->photopath);
             $fileTran->deleteFile(config('constants.awquickbytesimageextralargedir'), $photo->photopath);

            break;
        case 'sponsoredpost':
            $fileTran->deleteFile(config('constants.aw_sponsored_image_thumb_dir'), $photo->photopath);
            $fileTran->deleteFile(config('constants.aw_sponsored_image_extralarge_dir'), $photo->photopath);

            break;
        case 'album':
            $fileTran->deleteFile(config('constants.awalbumimagedir'), $photo->photopath);

            break;
        endswitch;
        $photo->delete();
    }
    
   function cropImage(Request $request){
       $sizeArray=  explode('X',$request->dimension);
      // print_r($sizeArray); exit;
      // echo 'tests'; exit;
      return view('layouts.crop', compact('sizeArray'));
   } 
   
   
   function resizeCropImage(Request $request){
       $sizeArray=  explode('X',$request->dimension);
      // print_r($sizeArray); exit;
      // echo 'tests'; exit;
      return view('layouts.resize', compact('sizeArray'));
   } 
   
   
    
}
