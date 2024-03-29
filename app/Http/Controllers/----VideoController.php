<?php
namespace App\Http\Controllers;
use App\Author;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use Input;
use Session;
use App\Right;
use App\MasterVideo;
use App\VideoCategory;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\Controller;
use App\Photo;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;
    public function __construct() {
         $this->rightObj= new Right();
    
    }
    
    
    public function index()
    {
        //echo count($arr);exit;
        //
        
        /* Right mgmt start */
        $rightId=62;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
                
        if(!Session::has('users')){
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        //Get QB Array
        $q = MasterVideo::where('video_status',1)
                ->select('id','video_title','updated_at','video_thumb_name');
            if (isset($_GET['searchin'])) {
                if ($_GET['searchin'] == 'title') {
                    $q->where('video_title', 'like', '%' . trim($_GET['keyword']) . '%');
                }
                if (@$_GET['searchin'] == 'id') {
                    $q->where('id', trim($_GET['keyword']));
                }
            }
            

       $videos=$q->where('channel_id','=',$currentChannelId)->groupby('id')->paginate(config('constants.recordperpage'));
       return view('video.published', compact('videos','channels','currentChannelId'));

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
        
        /* Right mgmt start */
        $rightId=65;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        $category = DB::table('category')->where('channel_id',$currentChannelId)->where('valid','1')->orderBy('name')->get();
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        //$channels = VideoController::getUserChannels($uid);
        $authors = Author::where('author_type_id','=',2)->get();
        $tags = Tag::where('valid','1')->get();
        $campaign = DB::table('campaign')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $p1= DB::table('author_type')->where('valid','1')->whereIn('author_type_id',[1,2])->lists('label','author_type_id');
        //fclose($asd);
        return view('video.upload-new-Video', compact('uid','channels','p1','authors','tags','currentChannelId','campaign','category'));
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
        /* Right mgmt start */
        $rightId=65;
        $currentChannelId=$request->channel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        $uid = $request->user()->id;
        $video = new MasterVideo();
       if($request ->video_name !=''){
            $destination_path = 'uploads/';
            $file = $request->file('video_name');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('video_name')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('video_name')->move($destination_path, $filename);
            $video->video_name = $filename;
            
            
            $s3 = AWS::createClient('s3');
            
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awvideo').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
            
            
            unlink($destination_path.$filename);
                  
        }

        if($request ->video_thumb_name !=''){
            $destination_path = 'uploads/';
            $file = $request->file('video_thumb_name');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('video_thumb_name')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('video_thumb_name')->move($destination_path, $filename);
            $video->video_thumb_name = $filename;
            
            
            $s3 = AWS::createClient('s3');
            $oldPhotos=MasterVideo::where('id',$request->faid)->get();
            foreach($oldPhotos as $ph){
                $s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awvideothumb').$ph->video_thumb_name
                        ));
            }
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awvideothumb').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                }
        }

        // Add Arr Data to Album Table //
        $video->channel_id = $request->channel;
        $video->video_title = $request->video_title;
        $video->video_summary = $request->video_summary;
        $video->tags = $request->Taglist;
        $video->video_status = '1';
        $video->campaign_id = $request->campaign;
        $video->save();
        $id = $video->id;
        
       //video Category - Save
            for ($i = 1; $i <= 4; $i++) {
                $video_category = new VideoCategory();
                $video_category->video_id = $id;
                $label = "category" . $i;
                if ($request->$label == '') {
                    break;
                }
                $video_category->category_id = $request->$label;
                $video_category->level = $i;
                $video_category->save();
            }

            Session::flash('message', 'Your video has been Upload successfully.');
            return redirect('/video/list?channel='.$request->channel);
        
      
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {   
       
        $video=MasterVideo::find($id);
        
        /* Right mgmt start */
        $rightId=65;
        $currentChannelId=$video->channel_id;
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
       $tags=  json_encode(DB::table('tags')
                ->select('tags_id as id','tag as name')
                ->whereIn('tags_id',explode(',',$video->tags))->get());
       $campaign = DB::table('campaign')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
       $category = DB::table('category')->where('channel_id','=',$currentChannelId)->where('valid','1')->orderBy('name')->get();
       $acateg2 = DB::table('video_category')
                    ->where('video_id', '=', $id)->get();
        
        $cateStr = array();
        $acateg = array();
        
        foreach ($acateg2 as $ac) {
            $lable = 'c' . $ac->level;
            $cateStr[$lable] = $ac->category_id;
                    
            switch ($ac->level) {
                case "1":
                    $catlbl = DB::table('category')->where('category_id', '=', $ac->category_id)->get();
                    $acateg[0]['level'] = 1;
                    $acateg[0]['category_id'] = $ac->category_id;
                    $acateg[0]['name'] = $catlbl[0]->name;
                    break;
                case "2":
                    $catlbl = DB::table('category_two')->where('category_two_id', '=', $ac->category_id)->get();
                    $acateg[1]['level'] = 2;
                    $acateg[1]['category_id'] = $ac->category_id;
                    $acateg[1]['name'] = $catlbl[0]->name;
                    ;
                    break;
                case "3":
                    $catlbl = DB::table('category_three')->where('category_three_id', '=', $ac->category_id)->get();
                    $acateg[2]['level'] = 3;
                    $acateg[2]['category_id'] = $ac->category_id;
                    $acateg[2]['name'] = $catlbl[0]->name;
                    break;
                case "4":
                    $catlbl = DB::table('category_four')->where('category_four_id', '=', $ac->category_id)->get();
                    $acateg[3]['level'] = 4;
                    $acateg[3]['category_id'] = $ac->category_id;
                    $acateg[3]['name'] = $catlbl[0]->name;
                    ;
                    break;
            }
            
        }

        if (!isset($acateg[0])) {
            unset($acateg[1]);
            unset($acateg[2]);
            unset($acateg[3]);
        } elseif (!isset($acateg[1])) {
            unset($acateg[2]);
            unset($acateg[3]);
        } elseif (!isset($acateg[2])) {
            unset($acateg[3]);
        }
       
        $uid = Session::get('users')->id;
        //$channels = VideoController::getUserChannels($uid);
        return view('video.edit', compact('video','tags','channels','campaign','acateg','category'));
       
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
        
        /* Right mgmt start */
        $rightId=65;
        $currentChannelId=$request->channel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        $video = MasterVideo::find($request->id);
       
       if($request ->video_name !=''){
            $destination_path = 'uploads/';
            $file = $request->file('video_name');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('video_name')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('video_name')->move($destination_path, $filename);
            $video->video_name = $filename;
            
            
            $s3 = AWS::createClient('s3');
            
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awvideo').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
           if($result['@metadata']['statusCode']==200){
            unlink($destination_path.$filename);
             }
                  
        }else{
            $video->video_name = $request->video_name_second;
        }

        if($request ->video_thumb_name !=''){
            $destination_path = 'uploads/';
            $file = $request->file('video_thumb_name');
          //  fwrite($asd, " File name:".$file."  \n");
            $filename = str_random(6) . '_' . $request->file('video_thumb_name')->getClientOriginalName();
            //$file->move($destination_path, $filename);
            $request->file('video_thumb_name')->move($destination_path, $filename);
            $video->video_thumb_name = $filename;
            
            
            $s3 = AWS::createClient('s3');
            $oldPhotos=MasterVideo::where('id',$request->faid)->get();
            foreach($oldPhotos as $ph){
                $s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awvideothumb').$ph->video_thumb_name
                        ));
            }
            
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awvideothumb').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                }
        }else{
            $video->video_thumb_name = $request->video_thumb_name_second;
        }
       
        $video->channel_id = $request->channel;
        $video->video_title = $request->video_title;
        $video->video_summary = $request->video_summary;
        $video->tags = $request->Taglist;
        $video->video_status = '1';
        $video->campaign_id = $request->campaign;
        $video->save();
        
        $id = $request->id;
        DB::table('video_category')->where('video_id','=',$id)->delete();
        //video Category - update
        for ($i = 1; $i <= 4; $i++) {
            $video_category = new VideoCategory();
            $video_category->video_id = $id;
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            $video_category->category_id = $request->$label;
            $video_category->level = $i;
            $video_category->save();
        }
        
        
       
            
        Session::flash('message', 'Your Video has been Published successfully.');
        return redirect('/video/list?channel='.$request->channel);
       
       
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
            $deleteAl = MasterVideo::find($d);
            $deleteAl->video_status = 0;
            $deleteAl->save();
        }
        return;
    }
    
    public function returnJson() {
        //DB::enableQueryLog();
        $matchText = $_GET['q'];
        $video = new MasterVideo;
        //->all()
        $rst = $video->where('video_title', "like", $matchText . '%')->select('id as id', 'video_title as name')->get();
          return response()->json($rst);
    }

}

