<?php
namespace App\Http\Controllers;

use App\Right;
use App\UserRight;
use App\User;
use App\Video;
use Illuminate\Http\Request;
//use DB;
use Session;
use App\Podcastaudiolist;
use App\Podcast;
use App\Channel;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Aws\Laravel\AwsFacade as AWS;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsServiceProvider;
use App\Classes\Zebra_Image;
use App\Classes\UploadHandler;

class PadcastController extends Controller {

    private $rightObj;

    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }

    
    

    





    

    public function getRights($uid, $parentId = 0) {
        //DB::enableQueryLog();
        $rights = DB::table('rights')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->where('user_rights.user_id', '=', $uid)
                ->where(function($rts) use ($parentId) {
                    $rts->where('rights.parent_id', '=', 0)->orwhere('rights.parent_id', '=', $parentId);
                })
                ->get();


        return $rights;
    }

    public function create() {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        
        $uid = Session::get('users')->id;

        /* Right mgmt start */
        $rightId = 2;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $postsArr = Podcast::where('status', 1)->get();
        

        return view('podcast.create', compact('channels','postsArr','currentChannelId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
         //echo '<pre>';
         //print_r($request->all());
        // Add Arr Data to Podcast album Table //
        $imageurl = '';
        $fileTran = new FileTransfer();
        
        if ($request->file('file')) {  //echo 'test';exit;
            $file = $request->file('file');
             
            $filename = str_random(6) . '_' . $request->file('file')->getClientOriginalName();
           
            //$fileTran->uploadFile($file,config('constants.awmagazinedir'), $filename); 
            //$source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $filename;
            
            $source = config('constants.awpodcastimageextralargedir');
            //$dest = config('constants.awpodcastimageextralargedir');
            //echo $dest;
            //echo 'test';
            $fileTran->uploadFile($file,config('constants.awpodcastimageextralargedir'), $filename,false); 
            //$fileTran->tranferFile($filename, $source, $dest, false);
            
            $destination = config('constants.awpodcastimagethumbtdir');
            $fileTran->resizeAndTransferFile($filename, '90X63', $source, $destination);
            $destination = config('constants.awpodcastimagemediumdir');
            $fileTran->resizeAndTransferFile($filename, '349X219', $source, $destination);
            
            
            $imageurl = $filename;
        }


        $podcast = new Podcast();
        
        $podcast->channel_id = $request->channel;
        $podcast->author_id = 1;
        $podcast->album_name = $request->album_name;
        $podcast->album_description = $request->album_description;
        $podcast->tags = $request->Taglist;
        $podcast->album_photo = $imageurl;
        $podcast->status = 1;
        $podcast->save();
       
        Session::flash('message', 'Your Podcast has been Published successfully.');
        return redirect('/padcast/create?channel=' . $request->channel);
        //return 'sumit save';
    }

public function destroy() {

        if (!Session::has('users')) {
            return 'Please login first.';
        }

        /* Right mgmt start */
        $rightId = 13;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId)) {
            return 'You are not authorized to access';
        }
        /* Right mgmt end */


        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        
        foreach ($delArr as $d) {
            $deleteAl = Podcast::find($d);
                $deleteAl->status = '0';
                $deleteAl->save();
        }
        return 'success';
    }
public function audiodelete() {

        if (!Session::has('users')) {
            return 'Please login first.';
        }

        /* Right mgmt start */
        $rightId = 13;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId)) {
            //return 'You are not authorized to access';
        //}
        /* Right mgmt end */


        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        
        foreach ($delArr as $d) {
            $deleteAl = Podcastaudiolist::find($d);
                $deleteAl->status = '0';
                $deleteAl->save();
        }
        return 'success';
    }

public function isfeature() {

        if (!Session::has('users')) {
            return 'Please login first.';
        }

        /* Right mgmt start */
        $rightId = 13;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId)) {
            return 'You are not authorized to access';
        }
        /* Right mgmt end */


        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
       
       
            $isFAl = Podcast::find($id);
                $isFAl->isf = '1';
                $isFAl->save();
        
        return 'success';
    }

 /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function podcastalbumlist(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            //return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
        if (isset($_GET['id'])) {
            $idalbum = $_GET['id'];
        }
         //echo '<pre>';
         //print_r($request->all());
        // Add Arr Data to Podcast album Table //
        $channels = $this->rightObj->getAllowedChannels($rightId);
        $postsArr = Podcastaudiolist::where('p_a_id', $idalbum)->where('status', 1)->get();
        return view('podcast.uloadaudio', compact('currentChannelId','channels','idalbum','postsArr'));
        
    }

/**
     * Store a edit  resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
         if (isset($_GET['editid'])) {
            $id = $_GET['editid'];
        }

        $postsArr = Podcast::where('id', $id)->where('status', 1)->first();
        //$quickbyte = QuickByte::find($id);
        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId))
          //  return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
        $tags = json_encode(DB::table('tags')
                        ->select('tags_id as id', 'tag as name')
                        ->whereIn('tags_id', explode(',', $postsArr->tags))->get());
        $channels = $this->rightObj->getAllowedChannels($rightId);
        
        return view('podcast.edit', compact('channels','postsArr','currentChannelId','tags'));
        
    }

/**
     * Store a edit audio  resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function audioedit(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
         if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }

        $postsArr = Podcastaudiolist::where('id', $id)->where('status', 1)->first();
        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId))
          //  return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
        $tags = json_encode(DB::table('tags')
                        ->select('tags_id as id', 'tag as name')
                        ->whereIn('tags_id', explode(',', $postsArr->tags))->get());
        $channels = $this->rightObj->getAllowedChannels($rightId);
        
        return view('podcast.editaudio', compact('channels','postsArr','currentChannelId','tags'));
        
    }


/**
     * Store a update  resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
         if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }

      
        
        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId))
          //  return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
        
         $imageurl = '';
        $fileTran = new FileTransfer();
        
        if ($request->file('file')) {  //echo 'test';exit;
            $file = $request->file('file');
             
            $filename = str_random(6) . '_' . $request->file('file')->getClientOriginalName();
           
            //$fileTran->uploadFile($file,config('constants.awmagazinedir'), $filename); 
            //$source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $filename;
            
            $source = config('constants.awpodcastimageextralargedir');
            //$dest = config('constants.awpodcastimageextralargedir');
            //echo $dest;
            //echo 'test';
            $fileTran->uploadFile($file,config('constants.awpodcastimageextralargedir'), $filename,false); 
            //$fileTran->tranferFile($filename, $source, $dest, false);
            
            $destination = config('constants.awpodcastimagethumbtdir');
            $fileTran->resizeAndTransferFile($filename, '90X63', $source, $destination);
            $destination = config('constants.awpodcastimagemediumdir');
            $fileTran->resizeAndTransferFile($filename, '349X219', $source, $destination);
            
            
            $imageurl = $filename;
        }
        $podcast = Podcast::find($id);
        $podcast->channel_id = $request->channel;
        $podcast->author_id = 3;
        $podcast->album_name = $request->album_name;
        $podcast->album_description = $request->album_description;
        $podcast->tags = $request->Taglist;
        if($imageurl !=''){
        $podcast->album_photo = $imageurl;
        }else{
        
        $podcast->album_photo = $request->album_photo;
        }
        $podcast->status = 1;
        $podcast->save();
       
        Session::flash('message', 'Your Podcast has been Update successfully.');
        return redirect('/padcast/create?channel=' . $request->channel);
        
    }

 public function storeaudio(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        
        
        $uid = $request->user()->id;
        $images = explode(',', $request->uploadedImages);
        $fileTran = new FileTransfer();
        foreach ($images as $image) {
               
            if (isset($request->imagetitle[$image])) {
                
                $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
                $source = '';
                $dest = config('constants.awpodcastaudiodir');
                $fileTran->tranferFile($image, $source, $dest, false);
                
                if (is_file($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image))
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image);
                if (is_file($source_thumb))
                    unlink($source_thumb);
                 
                $PodcastaudioEntry = new Podcastaudiolist();
                $PodcastaudioEntry->title = $request->imagetitle[$image];
                $PodcastaudioEntry->description = $request->imagedesc[$image];
                echo $PodcastaudioEntry->tags = $request->Taglist[$image];
                $PodcastaudioEntry->audio_name = $image;
                $PodcastaudioEntry->channel_id = $request->channel;
                $PodcastaudioEntry->p_a_id = $request->idalbum;
                $PodcastaudioEntry->status = '1';
                $PodcastaudioEntry->save();
            }
        }

        if ($request->status == 'P') {
            Session::flash('message', 'Your Quickbte has been Published successfully.');
            return redirect('/padcast/create?channel=' . $request->channel);
        }

    }

    /**
     * Store a update audio resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function updateaudio(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
         if (isset($_POST['id'])) {
            $id = $_POST['id'];
        }

      
        
        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        //if (!$this->rightObj->checkRights($currentChannelId, $rightId))
          //  return redirect('/dashboard');
        /* Right mgmt end */
        $uid = $request->user()->id; 
        
         $imageurl = '';
        $fileTran = new FileTransfer();
        
        if ($request->file('file')) {  //echo 'test';exit;
           $file = $request->file('file');
            $filename = str_random(6) . '_' . $request->file('file')->getClientOriginalName();
            $fileTran->uploadFile($file, config('constants.awvideo'), $filename,false); 
            
            $imageurl = $filename;
        }
        $Podcastaudiolist = Podcastaudiolist::find($id);
        $Podcastaudiolist->channel_id = $request->channel;
        $Podcastaudiolist->title = $request->title;
        $Podcastaudiolist->description = $request->description;
        $Podcastaudiolist->tags = $request->Taglist;
        if($imageurl !=''){
        $Podcastaudiolist->audio_name = $imageurl;
        }else{
        
        $Podcastaudiolist->audio_name = $request->album_photo;
        }
        $Podcastaudiolist->status = 1;
        $Podcastaudiolist->save();
       
        Session::flash('message', 'Your Podcast has been Update successfully.');
        return redirect('/padcast/create?channel=' . $request->channel);
        
    }

    
}
