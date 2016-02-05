<?php

namespace App\Http\Controllers;
use App\Right;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Photo;
use App\Video;
use App\SponsoredPost;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Classes\UploadHandler;
use App\Classes\Zebra_Image;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
class SponsoredPostsController extends Controller {
     private $rightObj;
    public function __construct() {
         $this->rightObj= new Right();
    
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($option) {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch ($option) {
            case "published":
                $valid = '1';
                $rightId=28;
                break;
            case "deleted":
                $valid = '0';
                $rightId=29;
                break;
        }
        
        
         /* Right mgmt start */
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        //Get QB Array
        //$qbytes = QuickByte::where('status',$status);
        $sposts = SponsoredPost::where('valid',$valid)->where('channel_id',$currentChannelId)->paginate(config('constants.recordperpage'));
        
        return view('sposts.' . $option, compact('sposts','channels','currentChannelId'));
           
    }

    public function getRights($uid, $parentId = 0) {

        $rights = DB::table('rights')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->where('user_rights.user_id', '=', $uid)
                ->where('rights.parent_id', '=', 0)
                ->Orwhere('rights.parent_id', '=', $parentId)
                ->get();

        return $rights;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    
    function imageUpload() {
        //  echo 'test';exit;
        $arg['script_url'] = url('sposts/image/upload');
        $upload_handler = new UploadHandler($arg);
    }
    
    public function create() { 
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
       
        $uid = Session::get('users')->id;
        /* Right mgmt start */
        $rightId=27;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        
        /* Right mgmt end */    
        
        //$channel_arr = SponsoredPostsController::getUserChannels($uid);
        $category = DB::table('category')->where('valid', '1')->where('channel_id',$currentChannelId)->get();
        $event = DB::table('event')->where('valid', '1')->where('channel_id',$currentChannelId)->get();
        $photos = DB::table('photos')->where('valid', '1')->get();
        //videos in Edit mode

        return view('sposts.create', compact('uid', 'channels', 'category', 'event', 'photos','currentChannelId'));
    }

    /**
     * Get channel Array for User ID
     *
     * @param User ID
     * @return Array
     */
    public function getUserChannels($userID) {

        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $userID)
                ->get();

        return $channels;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
//       echo '<pre>';
//       print_r($request->all());
        
        /* Right mgmt start */
        $rightId=27;
        $currentChannelId=$request->channel_sel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
         /* Right mgmt start */
        
        $uid = $request->user()->id;
        $spost = new SponsoredPost();
        $spost->channel_id = $request->channel_sel;
        $spost->title = $request->title;
        $spost->summary = $request->summary;
        $spost->description = $request->description;
        $spost->category1 = $request->category1;
        $spost->category2 = $request->category2;
        $spost->category3 = $request->category3;
        $spost->category4 = $request->category4;
        $spost->event_id = $request->event;
        $spost->status = $request->status;
        $spost->feature_this = $request->feature ? 1 : 0;
        if ($request->status == 'P') {
            $spost->published_by = $request->id;
            $spost->publish_date = date('Y-m-d');
            $spost->publish_time = date('H:i:s');
        }
        $spost->add_date = date('Y-m-d H:i:s');
        $spost->save();
        $id=$spost->id;
        if (trim($request->videoTitle) || trim($request->videoCode) || trim($request->videoSource) || trim($request->videoURL)) {
            $objVideo = new Video();
            $objVideo->title = $request->videoTitle;
            $objVideo->code = $request->videoCode;
            $objVideo->source = $request->videoSource;
            $objVideo->url = $request->videoURL;
            $objVideo->channel_id = $request->channel_sel;
            $objVideo->owned_by = 'sponsoredpost';
            $objVideo->owner_id = $id;
            $objVideo->added_by = $uid;
            $objVideo->added_on = date('Y-m-d');
            $objVideo->save();
        }
        if(trim($request->uploadedImages)){
        $images = explode(',', $request->uploadedImages);
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        $s3 = AWS::createClient('s3');
        foreach ($images as $image) { //echo 'foreach--';
            $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_dir') . $image;
            //echo $source; echo '<br>'; echo $dest;
            
            if (@copy($source, $dest)) { //echo 'copied--';

                $imaged = new Zebra_Image();

                // indicate a source image
                $imaged->source_path = $dest;
                
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_thumb_dir') . $image;
                if ($imaged->resize(160, 90, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.aw_sponsored_image_thumb_dir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
               
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_extralarge_dir') . $image;
                if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.aw_sponsored_image_extralarge_dir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
                // echo 'before unlink'; exit;
                unlink($source);
                unlink($source_thumb);
                unlink($dest);
                $articleImage = new Photo();
                $articleImage->photopath = $image;
                $articleImage->imagefullPath = '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'sponsoredpost';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            }

        }
        }
               //If has been Published/Deleted by Editor
        if ($request->status == 'P') {
            Session::flash('message', 'Your Sponsored Post has been Published successfully. It will appear on website shortly.');
            $page = 'published';
        }

        if ($request->status == 'D') {
            Session::flash('message', 'Your Sponsored Post has been Deleted successfully.');
            $page = 'deleted';
        }
        //fclose($asd);
        return redirect('/sposts/list/' . $page.'?channel='.$currentChannelId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
     
        $uid = Session::get('users')->id;
        if (!($uid)) {
            return redirect('/auth/login');
        }
        $spost = SponsoredPost::find($id);
        
         /* Right mgmt start */
        $rightId=27;
        $currentChannelId=$spost->channel_id;
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
         /* Right mgmt start */
       // echo '<pre>';
       //  print_r($spost); exit;
        
        //fwrite($asd, "EDIT ARTICLE ID::".$article->article_id." \n");                

        $event = DB::table('event')->where('valid', '1')->get();
        //$channels = SponsoredPostsController::getUserChannels($uid);
        $category = DB::table('category')->where('channel_id','=',$currentChannelId)->where('valid', '1')->get();
        
       // print_r($category);exit;
        
        $category2 = DB::table('category_two')->where('category_id', $spost->category1)->where('valid', '1')->get();
        $category3 = DB::table('category_three')->where('category_two_id', $spost->category2)->where('valid', '1')->get();
        $category4 = DB::table('category_four')->where('category_three_id', $spost->category4)->where('valid', '1')->get();
        
        $event = DB::table('event')->where('channel_id','=',$currentChannelId)->where('valid', '1')->get();
        $photos = DB::table('photos')->where('valid', '1')
                ->where('owned_by', 'sponsoredpost')
                ->where('owner_id', $id)
                ->get();
        $arrVideo = Video::where('owned_by', '=', 'sponsoredpost')
                        ->where('owner_id', '=', $id)->get();

        //fclose($asd);

        return view('sposts.edit', compact('uid', 'spost', 'channels','currentChannelId','category', 'category2', 'category3', 'category4', 'event', 'photos', 'arrVideo'));

        //return view('sposts.edit', compact('article','rights','channels','p1','postAs','country','states','newstype','category','magazine','event','campaign','columns','tags','photos','acateg','arrAuth','arrTags','arrVideo','userTup','arrTopics'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        //$channels = QuickBytesController::getUserChannels($uid);
        //$authors = Author::where('author_type_id','=',2)->get();
        //$tags = Tag::where('valid','1')->get();
        //fclose($asd);
        $channel_arr = SponsoredPostsController::getUserChannels($uid);
        $category = DB::table('category')->where('valid', '1')->get();
        $event = DB::table('event')->where('valid', '1')->get();
        $photos = DB::table('photos')->where('valid', '1')->get();
        //videos in Edit mode

        return view('sposts.edit', compact('channel_arr', 'category', 'event', 'photos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {
        
        /* Right mgmt start */
        $rightId=27;
        $currentChannelId=$request->channel_sel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
         /* Right mgmt start */
        
        
        $uid = $request->user()->id;

//        fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");

        $id = $request->spid;
        //$spost = new SponsoredPost();
        $spost = SponsoredPost::find($request->spid);
        //echo $request->channel_sel; exit;
        $spost->channel_id = $request->channel_sel;
        $spost->title = $request->title;
        $spost->summary = $request->summary;
        $spost->description = $request->description;
        $spost->category1 = $request->category1;
        $spost->category2 = $request->category2;
        $spost->category3 = $request->category3;
        $spost->category4 = $request->category4;
        $spost->event_id = $request->event;
        //$spost->photos_id= $request->photos_id;
        //$spost->video= $request->video_id;
        $spost->status = $request->status;
        $spost->feature_this = $request->feature ? 1 : 0;
        //echo 'test';exit;
        $spost->save();
        
        $objVideo = Video::where('owner_id', $id)->where('owned_by', 'sponsoredpost')->first();
        if ($objVideo) {
            if (trim($request->videoTitle) || trim($request->videoCode) || trim($request->videoSource) || trim($request->videoURL)) {
                // Updating video if already exist;
                $objVideo->title = $request->videoTitle;
                $objVideo->code = $request->videoCode;
                $objVideo->source = $request->videoSource;
                $objVideo->url = $request->videoURL;
                $objVideo->save();
            } else {// deleting video if all field set to blank
                $objVideo->delete();
            }
        } else {// Adding video in case no existing entry
            if (trim($request->videoTitle) || trim($request->videoCode) || trim($request->videoSource) || trim($request->videoURL)) {
                $objVideo = new Video();
                $objVideo->title = $request->videoTitle;
                $objVideo->code = $request->videoCode;
                $objVideo->source = $request->videoSource;
                $objVideo->url = $request->videoURL;
                $objVideo->channel_id = $request->channel_sel;
                $objVideo->owned_by = 'sponsoredpost';
                $objVideo->owner_id = $id;
                $objVideo->added_by = $uid;
                $objVideo->added_on = date('Y-m-d');
                $objVideo->save();
            }
        }
        
        
        if(trim($request->uploadedImages)){
        $images = explode(',', $request->uploadedImages);
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        $s3 = AWS::createClient('s3');
        foreach ($images as $image) { //echo 'foreach--';
            $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_dir') . $image;
            //echo $source; echo '<br>'; echo $dest;
            
            if (@copy($source, $dest)) { //echo 'copied--';

                $imaged = new Zebra_Image();

                // indicate a source image
                $imaged->source_path = $dest;
                
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_thumb_dir') . $image;
                if ($imaged->resize(160, 90, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.aw_sponsored_image_thumb_dir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
               
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.sponsored_image_extralarge_dir') . $image;
                if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.aw_sponsored_image_extralarge_dir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
                // echo 'before unlink'; exit;
                unlink($source);
                unlink($source_thumb);
                unlink($dest);
                $articleImage = new Photo();
                $articleImage->photopath = $image;
                $articleImage->imagefullPath = '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'sponsoredpost';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            }

        }
        }
        
        $page = '';
        //If has been Published/Deleted by Editor
        if ($request->status == 'P') {
            Session::flash('message', 'Your Sponsored Post has been Published successfully. It will appear on website shortly.');
            $page = 'published';
        }

        if ($request->status == 'D') {
            Session::flash('message', 'Your Sponsored Post has been Deleted successfully.');
            $page = 'deleted';
        }
        //fclose($asd);

        return redirect('/sposts/list/' . $page.'?channel='.$currentChannelId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
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
            $deleteSP = SponsoredPost::find($d);
            $deleteSP->valid = 0;
            $deleteSP->save();
        }
        return;
    }
    
     public function publishBulk() {
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        $uid = Session::get('users')->id;
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteS = SponsoredPost::find($d);
            $deleteS->status = 'P';
            $deleteS->valid='1';
            $deleteS->published_by=$uid;
            $deleteS->publish_date = date('Y-m-d');
            $deleteS->publish_time = date('H:i:s');
            $deleteS->save();
        }
        return 'success';
    }

}
