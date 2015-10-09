<?php

namespace App\Http\Controllers;

use App\Author;
use App\Tag;
use Illuminate\Http\Request;

use DB;
use Session;
use App\Album;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\Controller;
use App\Photo;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($option)
    {
        //echo count($arr);exit;
        //
        if(!Session::has('users')){
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch($option){
            case "published":
                $valid = '1';
                $rightLabel = "publishedAlbum";
                break;
            case "deleted":
                $valid = '0';
                $rightLabel = "deletedAlbum";
                break;
        }
       
        //Get QB Array
        $q = Album::where('album.valid',$valid)
                ->select('album.id','album.title','album.updated_at','photos.photopath')
                ->leftJoin('photos','album.id','=','photos.owner_id')
                ->where(function($qbytes){
                    $qbytes->whereNull('photos.owned_by')->orWhere('photos.owned_by','album') ;
                }) ;
        
            if (isset($_GET['searchin'])) {
                if ($_GET['searchin'] == 'title') {
                    $q->where('album.title', 'like', '%' . trim($_GET['keyword']) . '%');
                }
                if (@$_GET['searchin'] == 'id') {
                    $q->where('album.id', trim($_GET['keyword']));
                }
            }



        $albums=$q->groupby('album.id')->paginate(config('constants.recordperpage'));
        //qbytes = QuickByte::where('status',$status)->get();
       // echo count($qbytes);exit;
        $arrRights = AlbumController::getRights($uid);
//echo '<pre>';
//       print_r($arrRights);exit;
//        echo $rightLabel;echo '<br>';
        foreach($arrRights as $eachRight) {
            if ($rightLabel == $eachRight->label){
               //echo json_encode($albums);exit;
                return view('album.'.$option, compact('albums'));
            }
        }
       // echo 'test';exit;
       // exit;
        return redirect('/dashboard');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        $channels = AlbumController::getUserChannels($uid);
        $authors = Author::where('author_type_id','=',2)->get();
        $tags = Tag::where('valid','1')->get();
        $p1= DB::table('author_type')->where('valid','1')->whereIn('author_type_id',[1,2])->lists('label','author_type_id');
        //fclose($asd);
        return view('album.create', compact('uid','channels','p1','authors','tags'));
    }
    /*
     * Get Page Rights of the User
     */
    public function getRights($uid, $parentId=56){
        DB::enableQueryLog();
        
        $rights = DB::table('rights')
        ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
        ->where('user_rights.user_id','=',$uid)
        ->where(function($rts) use ($parentId){
                    $rts->where('rights.parent_id','=',0)->orwhere('rights.parent_id','=',$parentId) ;
             })
        ->get();
        $query = DB::getQueryLog();
        $lastQuery = end($query);
        //print_r($lastQuery); exit;
        return $rights;
    }
    /**
     * Get channel Array for User ID
     *
     * @param User ID
     * @return Array
     */
    public function getUserChannels($userID){

        $channels = DB::table('channels')
            ->join('rights','rights.pagepath','=','channels.channel_id')
            ->join('user_rights', 'user_rights.rights_id','=','rights.rights_id')
            ->select('channels.*')
            ->where('rights.label', '=', 'channel')
            ->where('user_rights.user_id', '=', $userID)
            ->orderBy('channel')    
            ->get();

        return $channels;
    }
    
    
    function imageUpload(){
      //  echo 'test';exit;
        $arg['script_url']=url('album/image/upload');
        $upload_handler = new UploadHandler($arg);
    }
    

    public function uploadImg(Request $request){

        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "Upload Images:".var_dump($_POST)." \n");
        //fclose($asd);
        return;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    { 
        
        $uid = $request->user()->id;
       
        //echo '<pre>';
       // print_r($request->all());exit;

        $album = new Album();

        // Add Arr Data to Album Table //
        $album->channel_id = $request->channel;
        $album->title = $request->title;
        $album->description = $request->featuredesc;
        $album->tags = $request->Taglist;
        $album->sponsored = ($request->is_sponsored)?'1':'0';
        $album->featured = ($request->is_featured)?'1':'0';
        $album->save();
        //Get Album id
        $id = $album->id;
        // Getting upladed images in array
        $images = explode(',', $request->uploadedImages);
            $c=0;
            // Copy uploaded from temporary location to specific location
            foreach ($images as $image) {
                $source=$_SERVER['DOCUMENT_ROOT'].'/files/'.$image;
                $source_thumb=$_SERVER['DOCUMENT_ROOT'].'/files/thumbnail/'.$image;
                $dest=$_SERVER['DOCUMENT_ROOT'].'/'.config('constants.albumimagedir').$image;
                if(@copy($source,$dest)){
                        unlink($source);
                        unlink($source_thumb);
                        $imageEntry=new Photo();
                        $imageEntry->title=$request->imagetitle[$c];
                        $imageEntry->description=$request->imagedesc[$c];
                        $imageEntry->source=$request->photosource[$c];;
                        $imageEntry->source_url=$request->sourceurl[$c];;
                        $imageEntry->photo_by=$request->photographby[$c];;
                        $imageEntry->photopath=$image;
                        $imageEntry->imagefullPath=url(config('constants.albumimagedir').$image);
                        $imageEntry->channel_id=$request->channel;
                        $imageEntry->owned_by='album';
                        $imageEntry->owner_id=$id;
                        $imageEntry->active='1';
                        $imageEntry->save();
                        $c++;
                }
        
            }
        //Redircting to specifi locationn with proper message
        if($request->status == 'P') {
            Session::flash('message', 'Your album has been Published successfully.');
            return redirect('/album/list/published');
        }
      
        if($request->status == 'D') {
            Session::flash('message', 'Your album has deleted successfully.');
            return redirect('/album/list/deleted');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {   
        $album=Album::find($id);
        $photos=DB::table('photos')
                ->where('owned_by','album')
                ->where('valid','1')
                ->where('owner_id',$id)->get();
       $tags=  json_encode(DB::table('tags')
                ->select('tags_id as id','tag as name')
                ->whereIn('tags_id',explode(',',$album->tags))->get());
        $uid = Session::get('users')->id;
        $channels = AlbumController::getUserChannels($uid);
        return view('album.edit', compact('album','photos','tags','channels'));
       
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
    public function update(Request $request)
    {//Update album
        $album = Album::find($request->id);
        // Add Arr Data to Article Table //
        $album->channel_id = $request->channel;
        $album->title = $request->title;
        $album->description = $request->featuredesc;
        $album->tags = $request->Taglist;
        $album->sponsored = ($request->is_sponsored)?'1':'0';
        $album->featured = ($request->is_featured)?'1':'0';
        $album->valid=($request->status == 'D')?'0':'1';
        $album->save();
        
       $id = $request->id;
       $images = explode(',', $request->uploadedImages);
            $c=0;
            // Copy uploaded from temporary location to specific location
            foreach ($images as $image) {
                $fname=time().rand(1,100).$image;
                $source=$_SERVER['DOCUMENT_ROOT'].'/files/'.$image;
                $source_thumb=$_SERVER['DOCUMENT_ROOT'].'/files/thumbnail/'.$image;
                $dest=$_SERVER['DOCUMENT_ROOT'].'/'.config('constants.albumimagedir').$fname;
                if(@copy($source,$dest)){
                        unlink($source);
                        unlink($source_thumb);
                        $imageEntry=new Photo();
                        $imageEntry->title=$request->imagetitle[$c];
                        $imageEntry->description=$request->imagedesc[$c];
                        $imageEntry->source=$request->photosource[$c];;
                        $imageEntry->source_url=$request->sourceurl[$c];;
                        $imageEntry->photo_by=$request->photographby[$c];;
                        $imageEntry->photopath=$fname;
                        $imageEntry->imagefullPath=url(config('constants.albumimagedir').$fname);
                        $imageEntry->channel_id=$request->channel;
                        $imageEntry->owned_by='album';
                        $imageEntry->owner_id=$id;
                        $imageEntry->active='1';
                        $imageEntry->save();
                        $c++;
                }
        
            }
        //If it's puublished
        if($request->status == 'P') {
            Session::flash('message', 'Your album has been Published successfully.');
            return redirect('/album/list/published');
        }
        // If deleted
        if($request->status == 'D') {
            Session::flash('message', 'Your Album has been dumped successfully.');
            return redirect('/album/list/deleted');
        }
       
        //fclose($asd);
       // return redirect('/quickbyte/list/published');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
        //
        //$asd = fopen("/home/sudipta/log.log", 'a+');

        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteAl = Album::find($d);
            $deleteAl->valid = 0;
            $deleteAl->save();
        }
        return;
    }
    
    public function publishBulk(){
         if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
         $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteAl = Album::find($d);
            $deleteAl->valid = 1;
            $deleteAl->save();
        }
        return;
    }
}
