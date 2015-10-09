<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Session;
use App\Photo;
use App\Video;
use App\SponsoredPost;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class SponsoredPostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($option)
    {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        if(!Session::has('users')){
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch($option){
            case "published":
                $status = 'P';
                $rightLabel = "publishedSPosts";
                break;
            case "deleted":
                $status = 'D';
                $rightLabel = "deletedSPosts";
                break;
        }
        //Get QB Array
        //$qbytes = QuickByte::where('status',$status);
        $sposts = SponsoredPost::where('status',$status)->where('valid','1')->get();
        $arrRights = SponsoredPostsController::getRights($uid);
        
        //fwrite($asd, " CHANNEL SELECTED ::" . $uid . " user_id :" . count($sposts) . " status:".$status."\n");
        
        foreach($arrRights as $eachRight) {
            if ($rightLabel == $eachRight->label){
                return view('sposts.'.$option, compact('sposts'));
            }
        }
        //fclose($asd);
        return redirect('/dashboard');
    }
    
    public function getRights($uid, $parentId=0){

        $rights = DB::table('rights')
        ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
        ->where('user_rights.user_id','=',$uid)
        ->where('rights.parent_id','=',0)
        ->Orwhere('rights.parent_id','=',$parentId)
        ->get();

        return $rights;
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
        //$channels = QuickBytesController::getUserChannels($uid);
        //$authors = Author::where('author_type_id','=',2)->get();
        //$tags = Tag::where('valid','1')->get();

        //fclose($asd);
        $channel_arr = SponsoredPostsController::getUserChannels($uid);
        $category = DB::table('category')->where('valid','1')->get();
        $event = DB::table('event')->where('valid','1')->get();
        $photos = DB::table('photos')->where('valid','1')->get();
        //videos in Edit mode
        
        return view('sposts.create',compact('uid','channel_arr','category','event','photos'));
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
            ->get();

        return $channels;
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {       
        //print_r($_POST);
        //exit;
        //Validate Data Received
        /*
        $validation = Validator::make($request->all(), [          
            $this->validate($request,[
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            'channel_sel' => 'required',
            'authortype' => 'required',
            'photo'     => 'required|image|mimes:jpeg,png|min:0|max:4'
        ]);
        */
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = $request->user()->id;

            //$asd = fopen("/home/sudipta/log.log", 'a+');
            //fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");
            //fclose($asd);

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
        //$spost->photos_id= $request->photos_id;
        //$spost->video= $request->video_id;
        $spost->status= $request->status;
        $spost->feature_this= $request->feature?1:0;
        if($request->status == 'P'){
        $spost->published_by= $request->id;
        $spost->publish_date = date('Y-m-d');
        $spost->publish_time = date('H:i:s');}        
        $spost->add_date = date('Y-m-d H:i:s');
        $pStr = '';       
        foreach($request->uploadedImages as $p){
            $pStr = $p.",";
        }
        $spost->photos_id = $pStr;
        $spost->video_id = $request->uploadedVideos[0];
        $spost->save();
        
        $id = $spost->id;
        //Add to photos + videos Table
        $arrP = explode(",",$request->uploadedImages[0]);
        $arrV = explode(",",$request->uploadedVideos[0]);
        //echo count($arrP);
     print_r($arrP);exit;
        //fwrite($asd, "SP Post fn:  ".$uid." 1v: ".$arrP[1]."\n");
        if( ((count($arrP)>0)) && ($arrP[1] != '') ){
            foreach($arrP as $p=>$val){
                if($p == 0){continue;}
                //fwrite($asd, "In loop Photo: p:".$p." val:".$val. "\n");
                $photo = Photo::find($val);                
                $photo->owner_id = $id;
                $photo->channel_id = $request->channel_sel;
                $photo->save();
            }
        }
        if( ((count($arrV)>0)) && ($arrV[0] != '') ){
            $video = Video::find($arrV[0]);            
            $video->owner_id = $id;
            $video->channel_id = $request->channel_sel;
            $video->save();
        }
        
        $page = '';
        //If has been Published/Deleted by Editor
        if($request->status == 'P') {
            Session::flash('message', 'Your Sponsored Post has been Published successfully. It will appear on website shortly.');
            $page = 'published';
        }
        
        if($request->status == 'D') {
            Session::flash('message', 'Your Sponsored Post has been Deleted successfully.');
            $page = 'deleted';
        } 
        //fclose($asd);
        return redirect('/sposts/list/'.$page);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //Check if Authenticated
        $uid = Session::get('users')->id;
            //Test this functionality
        if(!($uid)){return redirect('/auth/login');}

        //Check if has Access to Article ID
        //$userTup = User::find($uid);
        //Get User Rights
        //$rights = SponsoredPostsController::getRights($uid,8);

        //Get Article Tuple
        //$arti2 = Article::find($id);
        $spost = SponsoredPost::find($id);
        
        //fwrite($asd, " ONE :Has no right ::" . $uid . " user_id :" . $article->user_id . " \n");
        
        //Check if Article has Edit Page - Access
        //If Not - Flash Message & redirect
        /*$hasEditAccess = ArticlesController::hasRightOnArticle($uid, $article->user_id, $rights);
        if($hasEditAccess == 0){
            Session::flash('error', 'You do not have access on this Article.');
            return redirect('/article/list/new');
        }
        //Lock Article for Editor
        $addArticle = Article::find($id);
        $addArticle->locked_by = $uid;
        $addArticle->locked_at = date('Y-m-d H:i:s');
        $addArticle->save();
        */
        /*
        //Get Category 1,2,3,4
        $acateg1 = ArticleCategory::where('article_id','=',$id)->get();
        $acateg2 = DB::table('article_category')
                    ->join('category','article_category.category_id','=','category.category_id')
                    ->select('article_category.*','category.name')
                    ->where('article_id','=',$id)->get();
        //$cateStr = "{";
        $cateStr = array();
        $acateg = array();
        
        fwrite($asd, " COUNT OF ARR ::" . count($acateg) . " \n");
        foreach($acateg2 as $ac) {
            $lable = 'c' . $ac->level;
            $cateStr[$lable] = $ac->category_id;
            fwrite($asd, " Category Level ::" . $ac->level . " \n");
        
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
                    $acateg[1]['name'] = $catlbl[0]->name;;
                    break;
                case "3":
                    $catlbl = DB::table('category_three')->where('category_three_id', '=', $ac->category_id)->get();
                    $acateg[2]['level'] = 3;
                    $acateg[2]['category_id'] = $ac->category_id;
                    $acateg[2]['name'] = $catlbl[0]->name;;
                    break;
                case "4":
                    $catlbl = DB::table('category_four')->where('category_four_id', '=', $ac->category_id)->get();
                    $acateg[3]['level'] = 4;
                    $acateg[3]['category_id'] = $ac->category_id;
                    $acateg[3]['name'] = $catlbl[0]->name;;
                    break;
            }
        
            fwrite($asd, " Category IDs ::".$ac->category_id." Str: \n");
        
            //$cateStr.="\"c$ac->level\":\"$ac->category_id\",";
        }
        */
        //print_r($acateg);
        //exit;

        //Get Schedule Time

        $arty = DB::table('articles')
                    ->join('article_author','article_author.article_id','=','articles.article_id')
                    ->join('authors','authors.author_id','=','article_author.author_id')
                    ->select('articles.*','authors.name','authors.author_id')
                    ->where('articles.article_id',$id)
                    ->where('article_author.article_author_rank','1')
                    ->get();

        foreach($arty as $arty1){
            //$article = $arty1;
        }

        //fwrite($asd, "EDIT ARTICLE ID::".$article->article_id." \n");                

        $event = DB::table('event')->where('valid','1')->get();
        $channel_arr = SponsoredPostsController::getUserChannels($uid);
        $category = DB::table('category')->where('valid','1')->get();
        $category2 = DB::table('category_two')->where('category_id',$spost->category2)->where('valid','1')->get();
        $category3 = DB::table('category_three')->where('category_two_id',$spost->category2)->where('valid','1')->get();
        $category4 = DB::table('category_four')->where('category_three_id',$spost->category4)->where('valid','1')->get();
        $event = DB::table('event')->where('valid','1')->get();
        $photos = DB::table('photos')->where('valid','1')
                            ->where('owned_by','sponsoredpost')
                            ->where('owner_id',$id)
                            ->get();        
        $arrVideo = Video::where('owned_by','=','sponsoredpost')
                            ->where('owner_id','=',$id)->get();
        
        //fclose($asd);
        
        return view('sposts.edit',compact('uid','spost','channel_arr','category','category2','category3','category4','event','photos','arrVideo'));

        //return view('sposts.edit', compact('article','rights','channels','p1','postAs','country','states','newstype','category','magazine','event','campaign','columns','tags','photos','acateg','arrAuth','arrTags','arrVideo','userTup','arrTopics'));
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
        $category = DB::table('category')->where('valid','1')->get();
        $event = DB::table('event')->where('valid','1')->get();
        $photos = DB::table('photos')->where('valid','1')->get();
        //videos in Edit mode
        
        return view('sposts.edit',compact('channel_arr','category','event','photos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {
        //echo $HTTP_POST_VARS;
        print_r($_POST);
//exit;
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "Step 3.1 In Article POST Function \n");
        
        //Validate Data Received
        //Mark Non-Mandatory Data
        // Laters
        //$validation = Validator::make($request->all(), [
          /*
            $this->validate($request,[
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            'channel_sel' => 'required',
            'authortype' => 'required',
            'photo'     => 'required|image|mimes:jpeg,png|min:0|max:4'
        ]);
        */
        
        // NO CONDITION ------ For any Submit Other Than Drafts
        
        //Session's User Id
        $uid = $request->user()->id;
            
//        fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");
        
        $id = $request->spid;
        //$spost = new SponsoredPost();
        $spost = SponsoredPost::find($request->spid);
            
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
        $spost->status= $request->status;
        $spost->feature_this= $request->feature?1:0;
        //if($request->status == 'P'){
        //$spost->published_by= $request->id;
        //$spost->publish_date = date('Y-m-d');
        //$spost->publish_time = date('H:i:s');}        
        //$spost->add_date = date('Y-m-d H:i:s');
        $pStr = '';       
        foreach($request->uploadedImages as $p){
            $pStr = $p.",";
        }
        $spost->photos_id = $pStr;
        $spost->video_id = $request->uploadedVideos[0];
        $spost->save();
        
        $id = $spost->id;
        //Add to photos + videos Table
        //Video table (article_id)- Save
        DB::table('videos')->where('video_id', $request->uploadedVideos)
                ->update(['owner_id' => $id]);

        //Photos table (article_id + channel_id)- Save
        $arrIds = explode(',', $request->uploadedImages[0]);
            for ($i = 1; $i < count($arrIds); $i++) {
                DB::table('photos')->where('photo_id', $arrIds[$i])
                    ->update(['owner_id' => $id, 'channel_id' => $request->channel_sel]);
            }
        
        /*
        $arrP = explode(",",$request->uploadedImages[0]);
        $arrV = explode(",",$request->uploadedVideos[0]);
        echo count($arrP);
        fwrite($asd, "SP Post fn:  ".$uid." 1v: ".$arrP[1]."\n");
        if( ((count($arrP)>0)) && ($arrP[1] != '') ){
            foreach($arrP as $p=>$val){
                if($p == 0){continue;}
                fwrite($asd, "In loop Photo: p:".$p." val:".$val. "\n");
                $photo = Photo::find($val);                
                $photo->owner_id = $id;
                $photo->channel_id = $request->channel_sel;
                $photo->save();
            }
        }
        if( ((count($arrV)>0)) && ($arrV[0] != '') ){
            $video = Video::find($arrV[0]);            
            $video->owner_id = $id;
            $video->channel_id = $request->channel_sel;
            $video->save();
        }
        */
        $page = '';
        //If has been Published/Deleted by Editor
        if($request->status == 'P') {
            Session::flash('message', 'Your Sponsored Post has been Published successfully. It will appear on website shortly.');
            $page = 'published';
        }
        
        if($request->status == 'D') {
            Session::flash('message', 'Your Sponsored Post has been Deleted successfully.');
            $page = 'deleted';
        } 
        //fclose($asd);
        
        return redirect('/sposts/list/'.$page);
        
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
            $deleteSP = SponsoredPost::find($d);
            $deleteSP->valid = 0;
            $deleteSP->save();
        }
        return;
    }
}
