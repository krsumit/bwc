<?php

namespace App\Http\Controllers;

use App\Author;
use App\Tag;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Session;
use App\Debate;
use App\DebateCategory;
use App\DebateExpertView;
use App\DebateTag;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\Controller;
use App\Photo;
use App\Video;
use App\QuickbyteCategory;
use App\Classes\Zebra_Image;
use App\Classes\UploadHandler;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class DebateController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    
    private $rightObj;
    public function __construct() {
        $this->middleware('auth');
        $this->rightObj= new Right();
    
    }
    
    
    public function index() {
        //echo 'published debate'; exit;
        
        
        $uid = Session::get('users')->id;
        
        /* Right mgmt start */
        $rightId=79;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        
        $deb=  Debate::where('valid','1')->where('channel_id','=',$currentChannelId);
        $deb->orderBy('created_at','desc');
       
        if (isset($_GET['searchin'])) {
            if ($_GET['searchin'] == 'title') {
                $deb->where('title', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (@$_GET['searchin'] == 'id') {
                $deb->where('quickbyte.id', $_GET['keyword']);
            }
        }



        $debates = $deb->paginate(config('constants.recordperpage'));
        //echo count($debates); exit;
        return view('debate.published', compact('debates','channels','currentChannelId'));
       
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        
        /* Right mgmt start */
        $rightId=81;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $uid = Session::get('users')->id;
        $category = DB::table('category')->where('channel_id','=',$currentChannelId)->where('valid', '1')->orderBy('name')->get();
        return view('debate.create', compact('category', 'uid', 'channels','currentChannelId'));
    }

    /*
     * Get Page Rights of the User
     */

    public function getRights($uid, $parentId = 0) {

        $rights = DB::table('rights')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->where('user_rights.user_id', '=', $uid)
                ->where(function($rts) use ($parentId) {
                    $rts->where('rights.parent_id', '=', 0)->orwhere('rights.parent_id', '=', $parentId);
                })
                ->get();

        return $rights;
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
                ->orderBy('channel')
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
         // echo $request->is_featured; 
        
        /* Right mgmt start */
        $rightId=81;
        $currentChannelId=$request->channel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        
        if($request->is_featured){
            //echo 'test'; exit;
            $this->validate($request,[
            'channel' => 'required',      
            'title' => 'required',
            'debatedesc' => 'required', 
            'debateimage' => 'required'  
        ]);
        }else{
           $this->validate($request,[
            'channel' => 'required',      
            'title' => 'required',
            'debatedesc' => 'required', 
             
        ]); 
        }
         
        $uid = $request->user()->id;
        // echo '<pre>';
        //print_r($request->all());exit;

        $debate = new Debate();

        // Add Arr Data to Article Table //
        $debate->channel_id = $request->channel;
        $debate->title = $request->title;
        $debate->description = $request->debatedesc;
        $debate->valid = 1;
        $debate->is_featured=($request->is_featured)?1:0;
        $debate->save();
        $id = $debate->id;
        // Saving debate category
        for ($i = 1; $i <= 4; $i++) {
            $debateCategory = new DebateCategory();
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            $debateCategory->debate_id = $id;
            $debateCategory->cat_id = $request->$label;
            $debateCategory->cat_level = $i;
            $debateCategory->save();
        }
        
        // Saving tags 
         if ($request->Taglist) {
            $tagids = explode(',', $request->Taglist);
            $tagids = array_unique($tagids);
            foreach ($tagids as $key => $value) {
                $debateTag = new DebateTag();
                $debateTag->debate_id = $id;
                $debateTag->tag_id = $value;
                $debateTag->save();
            }
        }
        
        $s3 = AWS::createClient('s3');
        $fileTran = new FileTransfer();
        //Saving expert view1
        
       $destination_path = 'uploads/';
       $expertfilename1='';
       $expertfilename2='';
       if($request->hasFile('expertimage1')){
           $expertfilename1 = time().str_random(6) . '_' . $request->file('expertimage1')->getClientOriginalName();
           $request->file('expertimage1')->move($destination_path, $expertfilename1);
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.debateexpert').$expertfilename1,
                                'SourceFile'   => $destination_path.$expertfilename1,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $expertfilename1);
                }
           
       }
       
       if ($request->hasFile('expertimage1') || trim($request->expertname1) || trim($request->expertdesing1) || trim($request->experttwitter1) || trim($request->expertview1)) {
            $debeateView1 = new DebateExpertView();
            $debeateView1->debate_id = $id;
            $debeateView1->name=$request->expertname1;
            $debeateView1->designation=$request->expertdesing1;
            $debeateView1->expert_photo=$expertfilename1;
            $debeateView1->twitter_ac=$request->experttwitter1;
            $debeateView1->view=$request->expertview1;
            $debeateView1->save();
        }
        // saving experrt view2
       if ($request->hasFile('expertimage2')) {
            $expertfilename2 = time() . str_random(6) . '_' . $request->file('expertimage2')->getClientOriginalName();
            $request->file('expertimage2')->move($destination_path, $expertfilename2);
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.debateexpert') . $expertfilename2,
                'SourceFile' => $destination_path . $expertfilename2,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
                unlink($destination_path . $expertfilename2);
            }
        }
        
        if($request->hasFile('expertimage2') || trim($request->expertname2) || trim($request->expertdesing2) || trim($request->experttwitter2) || trim($request->expertview2)){
            $debeateView2 = new DebateExpertView();
            $debeateView2->debate_id = $id;
            $debeateView2->name=$request->expertname2;
            $debeateView2->designation=$request->expertdesing2;
            $debeateView2->expert_photo=$expertfilename2;
            $debeateView2->twitter_ac=$request->experttwitter2;
            $debeateView2->view=$request->expertview2;
            $debeateView2->save();
        }
       
        
        //Saving debate video 
        if (trim($request->videotitle) || trim($request->videocode) || trim($request->videosource) || trim($request->videourl)) {
            $objVideo = new Video();
            $objVideo->title = $request->videotitle;
            $objVideo->code = $request->videocode;
            $objVideo->source = $request->videosource;
            $objVideo->url = $request->videourl;
            $objVideo->channel_id = $request->channel;
            $objVideo->owned_by = 'debate';
            $objVideo->owner_id = $id;
            $objVideo->added_by = $uid;
            $objVideo->added_on = date('Y-m-d');
            $objVideo->save();
        }
        
        
        // Uploading and saving debate featured image
        if ($request->hasFile('debateimage')) {
            $debatefilename = time() . str_random(6) . '_' . $request->file('debateimage')->getClientOriginalName();
            $request->file('debateimage')->move($destination_path, $debatefilename);
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.debatefeatured') . $debatefilename,
                'SourceFile' => $destination_path . $debatefilename,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
                unlink($destination_path . $debatefilename);
            }
            $photo = new Photo();
            $photo->channel_id = $request->channel;
            $photo->owned_by = 'debate';
            $photo->owner_id = $id;
            $photo->valid = '1';
            $photo->photopath = $debatefilename;
            $photo->save();
        } 
        
       
            Session::flash('message', 'Your Debate has been Published successfully.');
            return redirect('/debate/published?channel='.$request->channel);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        $uid = Session::get('users')->id;
        
        $debateDetail=  Debate::find($id);
        
        
         /* Right mgmt start */
        $rightId=81;
        $currentChannelId=$debateDetail->channel_id;
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        $debatetags= json_encode(DebateTag::select('tags.tags_id as id', 'tags.tag as name')
                ->join('tags','tags.tags_id','=','debate_tag.tag_id')
                ->where('tags.valid','1')
                ->where('debate_tag.debate_id',$id)->get());
        
        $acateg2 = DB::table('debate_category')->where('debate_id','=',$id)->get();
        $cateStr = array();
        $acateg = array();
        foreach($acateg2 as $ac) {
            $lable = 'c' . $ac->cat_level;
            $cateStr[$lable] = $ac->cat_id;            
            //fwrite($asd, " Category Level ::" . $ac->level . " \n");            
            switch ($ac->cat_level) {
                case "1":
                    $catlbl = DB::table('category')->where('category_id', '=', $ac->cat_id)->get();
                    $acateg[0]['level'] = 1;
                    $acateg[0]['category_id'] = $ac->cat_id;
                    $acateg[0]['name'] = $catlbl[0]->name;
                    break;
                case "2":
                    $catlbl = DB::table('category_two')->where('category_two_id', '=', $ac->cat_id)->get();
                    $acateg[1]['level'] = 2;
                    $acateg[1]['category_id'] = $ac->cat_id;
                    $acateg[1]['name'] = $catlbl[0]->name;;
                    break;
                case "3":
                    $catlbl = DB::table('category_three')->where('category_three_id', '=', $ac->cat_id)->get();
                    $acateg[2]['level'] = 3;
                    $acateg[2]['category_id'] = $ac->cat_id;
                    $acateg[2]['name'] = $catlbl[0]->name;
                    break;
                case "4":
                    $catlbl = DB::table('category_four')->where('category_four_id', '=', $ac->cat_id)->get();
                    $acateg[3]['level'] = 4;
                    $acateg[3]['category_id'] = $ac->cat_id;
                    $acateg[3]['name'] = $catlbl[0]->name;;
                    break;
            }
            
        }
        //print_r($acateg); exit;
        // $category = DB::table('category')->where('valid','1')->orderBy('name')->get();
         $debateVideo = Video::where('owned_by', '=', 'debate')
                        ->where('owner_id', '=', $id)->first();
         $debatePhotos = Photo::where('owned_by', '=', 'debate')
                        ->where('owner_id', '=', $id)->first();
         //print_r($debatePhotos);exit;
          $expertnots= DebateExpertView::where('debate_id',$id)->get();
          //echo count($expertnots);exit;
          $exprtnts=array();
          foreach($expertnots as $notes){
             $exprtnts[]=$notes;
          }
          //echo gettype($debateVideo); exit;
        
          //print_r($debateDetail); exit;
          
        //echo $exprtnts[0]->view;exit;
        
        $category = DB::table('category')->where('channel_id','=',$currentChannelId)->where('valid', '1')->orderBy('name')->get();
        return view('debate.edit', compact('category','acateg','uid', 'channels','debateDetail','debatetags','debatePhotos','debateVideo','exprtnts'));
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
        $rightId=81;
        $currentChannelId=$request->channel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        if($request->is_featured && !trim($request->debate_old_featured_image)){
            //echo 'test'; exit;
            $this->validate($request,[
            'channel' => 'required',      
            'title' => 'required',
            'debatedesc' => 'required', 
            'debateimage' => 'required'  
        ]);
        }else{
            $this->validate($request,[
                'channel' => 'required',      
                'title' => 'required',
                'debatedesc' => 'required', 

            ]); 
        }
        
        //debate_old_featured_image
        $uid = $request->user()->id;
       // echo '<pre>';
       // print_r($request->all());exit;

        $debate =Debate::find($request->id);
        // Add Arr Data to Article Table //
        $debate->channel_id = $request->channel;
        $debate->title = $request->title;
        $debate->description = $request->debatedesc;
        $debate->valid = 1;
        $debate->is_featured=($request->is_featured)?1:0;
        $debate->update();
        $id = $request->id;
        //Deleting debate cateogry
        DB::table('debate_category')->where('debate_id','=',$id)->delete();
        // Saving debate category
        for ($i = 1; $i <= 4; $i++) {
            $debateCategory = new DebateCategory();
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            $debateCategory->debate_id = $id;
            $debateCategory->cat_id = $request->$label;
            $debateCategory->cat_level = $i;
            $debateCategory->save();
        }
        //Deleting debat category 
        DB::table('debate_tag')->where('debate_id','=',$id)->delete();
        // Saving tags 
         if ($request->Taglist) {
            $tagids = explode(',', $request->Taglist);
            $tagids = array_unique($tagids);
            foreach ($tagids as $key => $value) {
                $debateTag = new DebateTag();
                $debateTag->debate_id = $id;
                $debateTag->tag_id = $value;
                $debateTag->save();
            }
        }
        
        $s3 = AWS::createClient('s3');
        
        //Saving expert view1
        
       // Deleting old view of expert 
        DB::table('debate_expert_view')->where('debate_id','=',$id)->delete();
        
       $destination_path = 'uploads/';
       $expertfilename1='';
       $expertfilename2='';
       if($request->hasFile('expertimage1')){
           $expertfilename1 = time().str_random(6) . '_' . $request->file('expertimage1')->getClientOriginalName();
           $request->file('expertimage1')->move($destination_path, $expertfilename1);
            $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.debateexpert').$expertfilename1,
                                'SourceFile'   => $destination_path.$expertfilename1,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $expertfilename1);
                }
           
       }else{
           $expertfilename1=$request->expert_old_image1;
       }
       
       if ($request->hasFile('expertimage1') || trim($request->expertname1) || trim($request->expertdesing1) || trim($request->experttwitter1) || trim($request->expertview1) || trim($request->expert_old_image1) ) {
            $debeateView1 = new DebateExpertView();
            $debeateView1->debate_id = $id;
            $debeateView1->name=$request->expertname1;
            $debeateView1->designation=$request->expertdesing1;
            $debeateView1->expert_photo=$expertfilename1;
            $debeateView1->twitter_ac=$request->experttwitter1;
            $debeateView1->view=$request->expertview1;
            $debeateView1->save();
        }
        
        
        // saving expert view2
       if ($request->hasFile('expertimage2')) {
            $expertfilename2 = time() . str_random(6) . '_' . $request->file('expertimage2')->getClientOriginalName();
            $request->file('expertimage2')->move($destination_path, $expertfilename2);
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.debateexpert') . $expertfilename2,
                'SourceFile' => $destination_path . $expertfilename2,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
                unlink($destination_path . $expertfilename2);
            }
        }else{
           $expertfilename2=$request->expert_old_image2;
       }
        
        if($request->hasFile('expertimage2') || trim($request->expertname2) || trim($request->expertdesing2) || trim($request->experttwitter2) || trim($request->expertview2) || trim($request->expert_old_image2)){
            $debeateView2 = new DebateExpertView();
            $debeateView2->debate_id = $id;
            $debeateView2->name=$request->expertname2;
            $debeateView2->designation=$request->expertdesing2;
            $debeateView2->expert_photo=$expertfilename2;
            $debeateView2->twitter_ac=$request->experttwitter2;
            $debeateView2->view=$request->expertview2;
            $debeateView2->save();
        }
       
        // Deleting old videos 
        DB::table('videos')->where('owner_id','=',$id)->where('owned_by','=','debate')->delete();
        //Saving debate video 
        if (trim($request->videotitle) || trim($request->videocode) || trim($request->videosource) || trim($request->videourl)) {
            $objVideo = new Video();
            $objVideo->title = $request->videotitle;
            $objVideo->code = $request->videocode;
            $objVideo->source = $request->videosource;
            $objVideo->url = $request->videourl;
            $objVideo->channel_id = $request->channel;
            $objVideo->owned_by = 'debate';
            $objVideo->owner_id = $id;
            $objVideo->added_by = $uid;
            $objVideo->added_on = date('Y-m-d');
            $objVideo->save();
        }
        
        
        // Uploading and saving debate featured image
        if ($request->hasFile('debateimage')) {
            // Deleting old image if exist
            DB::table('photos')->where('owner_id','=',$id)->where('owned_by','=','debate')->delete();
            $debatefilename = time() . str_random(6) . '_' . $request->file('debateimage')->getClientOriginalName();
            $request->file('debateimage')->move($destination_path, $debatefilename);
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.debatefeatured') . $debatefilename,
                'SourceFile' => $destination_path . $debatefilename,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
                unlink($destination_path . $debatefilename);
            }
            $photo = new Photo();
            $photo->channel_id = $request->channel;
            $photo->owned_by = 'debate';
            $photo->owner_id = $id;
            $photo->valid = '1';
            $photo->photopath = $debatefilename;
            $photo->save();
        } 
        Session::flash('message', 'Your Debate has been updated successfully.');
        return redirect('/debate/published?channel='.$request->channel);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteDb = Debate::find($d);
            $deleteDb->valid =0;
            $deleteDb->save();
        }
        return;
    }

}
