<?php

namespace App\Http\Controllers; 

use App\FeatureBox;
use Illuminate\Http\Request;
use DB;
use Session;
use App\Photo;
use App\Video;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\PhotosController;
use App\Http\Requests;
use App\Right;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class FeatureBoxController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }

    public function index() {
        //
    }

    /**
     * Show the form for creating a new resource. - FB Interface
     *
     * @return Response
     */
    public function create() {
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
//        $asd = fopen("/home/sudipta/log.log", 'a+');

        /* Right mgmt start */
        $rightId = 19;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */


        $current = DB::table('featuredarticle')
                ->join('users', 'featuredarticle.editor_id', '=', 'users.id')
                ->select('featuredarticle.*', 'users.name')
                ->where('featuredarticle.featured', '=', '1')
                ->where('featuredarticle.channel_id', '=', $currentChannelId)
                ->where('featuredarticle.valid', '1')
                ->first();

        $old = DB::table('featuredarticle')
                ->join('users', 'featuredarticle.editor_id', '=', 'users.id')
                ->select('featuredarticle.*', 'users.name')
                ->where('featuredarticle.featured', '=', '0')
                ->where('featuredarticle.channel_id', '=', $currentChannelId)
                ->where('featuredarticle.valid', '=', '1')
                ->orderBy('updated_at', 'desc')
                ->get();
        return view('featurebox.index', compact('channels', 'current', 'old', 'uid', 'currentChannelId'));
    }

    /**
     * Get All Channels for the User Logged In
     *
     * @param User ID $uid
     * @return $channelArr Array
     */
    public function getUserChannels($uid) {

        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $uid)
                ->get();

        return $channels;
    }

    /**
     * Get Old (Inactive) Featured Articles
     *
     * @param None
     * @return Array
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {


        $uid = $request->user()->id;

        $featureB = "";
        
        
        /* Right mgmt start */
        $rightId = 19;
        $currentChannelId = $request->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        
        
        if($request->faid){
            $featureB = FeatureBox::find($request->faid);
        //    fwrite($asd, " Is for Edit ::" .$request->faid  . "\n\n");
        }//New Featured Article is being Added -
        else {
            //Unset existing Featured Article for Channel
            $current = FeatureBox::where('channel_id', '=', $request->channel_id)
                ->where('featured', '=', '1')->where('valid', '=', '1')->first();
            //Unset Existing ACtive FA
            if ($current) {
                $current->featured = '0';
                $current->save();
            }
            $featureB = new FeatureBox();
            $featureB->featured_on = date('Y-m-d H:i:s');

        }
        if($request ->position2_photo !=''){
            $destination_path = 'uploads/';
            $file = $request->file('position2_photo');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('position2_photo')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('position2_photo')->move($destination_path, $filename);
            $featureB->position2_photo = $filename;
            
            
            $s3 = AWS::createClient('s3');
            $oldPhotos=FeatureBox::where('id',$request->faid)->get();
            foreach($oldPhotos as $ph){
                $s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awfeaturebox2').$ph->position2_photo
                        ));
            }
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awfeaturebox2').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                }
        } else{
        if($request ->position2val_photo!=''){
             $featureB->position2_photo = $request ->position2val_photo;
             }
        }
        if($request ->position3_photo !=''){
            $destination_path = 'uploads/';
            $file = $request->file('position3_photo');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('position3_photo')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('position3_photo')->move($destination_path, $filename);
            $featureB->position3_photo = $filename;
           
            
            $s3 = AWS::createClient('s3');
            $oldPhotos=FeatureBox::where('id',$request->faid)->get();
            foreach($oldPhotos as $ph){
                $s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awfeaturebox3').$ph->position3_photo
                        ));
            }
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awfeaturebox3').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                }
        }else{
            
              if($request ->position3val_photo!=''){
            $featureB->position3_photo = $request ->position3val_photo;
            }
        }
        $featureB->title = $request->title;
        $featureB->description = $request->description;
        $featureB->url = $request->url;
        $featureB->channel_id = $request->channel_id;
        $featureB->editor_id = $uid;
       
       
        
        $featureB->position2_title = $request ->position2_title;
        $featureB->position2_url = $request ->position2_url;
        $featureB->position2_type = $request ->position2_type;
        $featureB->position3_title = $request ->position3_title;
        $featureB->position3_url = $request ->position3_url;
        $featureB->position3_type = $request ->position3_type;
        $featureB->featured = '1';
        $featureB->valid = '1';
        $featureB->save();

        //Retrieve Generated ID
        $fid = $featureB->id;

       // echo $request->mediaSel;
//die;
        //For Image or Video Added:
        if($request->mediaSel == 'photo' ){
            //echo 'sumit';
            
            $photo = new Photo();
            if($request->photo !=''){
            $destination_path = 'uploads/';
            $file = $request->file('photo');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('photo')->move($destination_path, $filename);

            $photo->photopath = $filename;
            
            $s3 = AWS::createClient('s3');
            $oldPhotos=Photo::where('owned_by','featurebox')->where('owner_id',$fid)->get();
            foreach($oldPhotos as $ph){
                $s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awfeaturebox').$ph->photopath
                        ));
            }
            Photo::where('owned_by','featurebox')->where('owner_id',$fid)->delete();
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awfeaturebox').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                }
            
            }else{
                $photo->photopath = $request->photo_photo;
            }
            $photo->channel_id = $request->channel_id;
            $photo->owned_by = 'featurebox';
            $photo->owner_id = $fid;
            $photo->valid = '1';
            $photo->save();
          echo  $pid = $photo->photo_id; 
         //echo  $fid;
          //die;
            //Updating Feature Article
            $fBEdit = FeatureBox::find($fid);
            $fBEdit->photo_id = $pid;
            $fBEdit->update();

            //$pid = PhotosController::store($request);
        }elseif($request->mediaSel == 'video' && $request->code!=''){
            $video = new Video();
            $video->code = $request->code;
            $video->channel_id = $request->channel_id;
            $video->owned_by = 'featurebox';
            $video->owner_id = $fid;
            $video->valid = '1';
            $video->save();
            $vid = $video->video_id;
            //Updating Feature Article
            $fBEdit = FeatureBox::find($fid);
            $fBEdit->video_id = $vid;
            $fBEdit->update();
        }
        /// fclose($asd);
        Session::flash('message', 'Your FeatureBox has been Published successfully.');
        return redirect('/featurebox?channel='.$request->channel_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        //
    }

    /**
     * Show the form for editing the specified resource. - Ajax Get
     *
     * @param  int  $id
     * @return Response
     */
    public function edit() {
        //
        //print_r($_POST);

        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //exit;
        //$asd = fopen("/home/sudipta/log.log", 'a+');


        $fb = FeatureBox::find($id);
        /*   ->join('videos','featuredarticle.video_id','=','videos.video_id')
          ->join('photos','featuredarticle.photo_id','=','photos.photo_id')
          ->get();
         */
        //fwrite($asd, " CURRENT SELECTED IFB ::" . $id . " " . count($fb) . " ID:" . $fb . "\n\n");
        if ($fb->video_id > 0) {
            $vd = Video::where('owner_id', $fb->id)->where('owned_by', 'featurebox')
                            ->select('video_id', 'code')->get();

            $user[] = json_decode($fb, true);
            foreach ($vd as $v) {
                $user[] = json_decode($v, true);
            }
            $fb = $json_merge = json_encode($user);
            /*
              $a1 = json_decode( $fb, true );
              //$a2 = json_decode( $vd, true );
              $res = array_merge_recursive( $a1, $vd );
              $fb = json_encode( $res );


              $r = [];
              foreach(json_decode($fb, true) as $key => $array){
              fwrite($asd, " KEY ::" . $key . " Arr:  ".$array."\n");
              $r[$key] = array_merge(json_decode($vd, true)[$key],$array);
              }
              $fb = json_encode($r);
             */
            //  fwrite($asd, " CURRENT SELECTED VD ::" . $id . " " . count($vd) . " ID:" . $vd . "\n\n");
            //  fwrite($asd, " CURRENT SELECTED FB ::" . $id . " " . count($fb) . " ID:" . $fb . "\n\n");
            //$fb = json_encode(array_merge(json_decode($fb, true),json_decode($vd, true)));
        } elseif (($fb->photo_id) && ($fb->photo_id > 0)) {
            $ph = Photo::where('owner_id', $fb->id)->where('owned_by', 'featurebox')
                            ->select('photo_id', 'photopath')->get();

            $user[] = json_decode($fb, true);
            foreach ($ph as $p) {
                $user[] = json_decode($p, true);
            }
            $fb = $json_merge = json_encode($user);

            //$user[] = json_decode($fb,true);
            //$user[] = json_decode($ph,true);
            //$fb = $json_merge = json_encode($user);
            //$fb = json_encode(array_merge(json_decode($fb, true),json_decode($ph, true)));
        } else {
            $user[] = json_decode($fb, true);
            $fb = $json_merge = json_encode($user);
        }
        //fwrite($asd, " CURRENT SELECTED ::" . $id . " ".count($fb) ." ID:".$fb."\n");
//fclose($asd);

        return $fb;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id) {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return None
     */
    public function destroy() {
        //
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //exit;
        $fb = FeatureBox::find($id);
        $fb->valid = 0;
        $fb->save();

        return;
    }

}
