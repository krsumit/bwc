<?php

namespace App\Http\Controllers;
use App\ArticleAuthor;
use App\ArticleCategory;
use App\ArticleTag;
use App\ArticleTopic;
use App\Category;
use App\Photo;
use App\Right;
use App\UserRight;
use App\User;
use App\Video;
use Illuminate\Http\Request;
//use DB;
use Session;
use App\Article;
use App\Channel;
use App\AuthorType;
use App\Country;
use App\State;
use App\Author;
use App\NewsType;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Classes\UploadHandler;
use App\Classes\Zebra_Image;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class ArticlesController extends Controller {

    private $rightObj;
    public function __construct() {
         $this->rightObj= new Right();
    
    }

    public function index($option) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        
        $rightId='';
        //exit;
        switch ($option) {
            case "drafts":
                $status = 'S';
                $page = "drafts";
                $rightLabel = "drafts";
                break;
            case "new":
                $status = 'N';
                $rightLabel = "newArticles";
                $rightId=11;
                break;
            case "scheduled":
                $status = 'SD';
                $rightLabel = "scheduledArticles";
                $rightId=15; 
                break;
            case "published":
                $status = 'P';
                $rightLabel = "publishedArticles";
                $rightId=16;
                break;
            case "deleted":
                $status = 'D';
                $rightLabel = "deletedArticles";
                $rightId=17;
                break;
        }
        
        /* Right mgmt start */
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $editor = '';
        //For My Drafts Page
        if ($option == 'drafts') {

            $q = DB::table('articles')
                    //->join('users');
                    ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                    ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id')
                    ->select(DB::raw('articles.article_id,articles.title,articles.article_id,articles.publish_date,articles.publish_time,
                    group_concat(authors.name) as name'))
                    ->where('status', $status)
                    ->where('user_id', $uid);

            if (isset($_GET['searchin'])) {
                if ($_GET['searchin'] == 'title') {
                    $q->where('articles.title', 'like', '%' . $_GET['keyword'] . '%');
                }
                if (@$_GET['searchin'] == 'article_id') {
                    $q->where('articles.article_id', $_GET['keyword']);
                }
            }
            $q->groupBy('articles.article_id');
            $q->orderBy('articles.article_id', 'DESC'); 
            $articles = $q->paginate(config('constants.recordperpage'));
        } else {
            $i = 0;
            $articles = array();
            DB::enableQueryLog();
            $condition = '';

            $q = DB::table('articles')
                    ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                    ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id');
            if ($option == 'new') {
                $q->join('users', 'articles.user_id', '=', 'users.id');
                $q->select(DB::raw('articles.article_id,articles.title,articles.created_at,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by,users.name as username'));
                $q->orderBy('articles.created_at', 'desc');
            } else {
                $q->select(DB::raw('articles.article_id,articles.title,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by'));
                if($option=='scheduled'){
                   $q->orderBy('articles.updated_at', 'desc'); 
                }else{
                  $q->orderBy('articles.publish_date', 'desc');
                  $q->orderBy('articles.publish_time', 'desc');  
                }
                
            }
            $q->where('articles.channel_id',$currentChannelId)
                    ->where('status', $status);
            if (isset($_GET['searchin'])) {
                if ($_GET['searchin'] == 'title') {
                    $q->where('articles.title', 'like', '%' . $_GET['keyword'] . '%');
                }
                if (@$_GET['searchin'] == 'article_id') {
                    $q->where('articles.article_id', $_GET['keyword']);
                }
                if (@$_GET['searchin'] == 'author') {
                    $q->where('authors.name', 'like', '%' . $_GET['keyword'] . '%');
                }
            }

            $articles = $q->groupBy('articles.article_id')->paginate(config('constants.recordperpage'));
            
            $editor = DB::table('users')
                    ->join('articles', 'users.id', '=', 'articles.locked_by')
                    ->select('users.name', 'articles.article_id')
                    ->where('status', $status)
                    ->get();
        }
        //echo $currentChannelId; exit;
        //print_r($channels); exit;
        return view('articles.' . $option, compact('articles', 'editor','channels','currentChannelId'));
        
    }

    function imageUpload() {

        //  echo 'test';exit;
        $arg['script_url'] = url('article/image/upload');
        $upload_handler = new UploadHandler($arg);
    }

    /*
     * Check if The User ID passed has Rights to Edit the Article
     *
     * passes User ID, Article ID, User Rights
     * @returns boolean 1:0
     */

    public function hasRightOnArticle($uid, $article_userid, $rights) {
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, " ONE :Has no right ::" . $uid . " user_id :" . $article_userid . " \n");

        $canAccess = 0;
        //Is the Author, Has user_id or Is EditArticle
        if (($uid == $article_userid)) {
            //Is the Owner of the Article - Continue
            $canAccess = 1;
            //fwrite($asd, " Has no right ::" . $uid . " user_id :" . $article_userid . " \n");
        } else {
            //echo '<pre>';
            //print_r($rights);
            $canAccess = 0;
            foreach ($rights as $right) {
                //Check if has right for Edit Article
                //fwrite($asd, "TWO Has no right ::" . $uid . " user_id :" . $right->label . " \n");
                if ($right->label == 'editArticle') { //echo 'passed';; exit;
                    //If has right - Continue
                    $canAccess = 1;
                    //fwrite($asd, " Has  ::" . $uid . " user_id :" . $article_userid . " \n");
                }
                /* else{
                  $canAccess = 0;
                  } */
            }
        }
        //fwrite($asd, " Return value ::" . $uid . " user_id :" . $canAccess . " \n");
        // fclose($asd);
        //echo $canAccess;exit;
        return $canAccess;
    }

    /*
     * Edit Article Display Proces
     *
     * @passes Article ID     *
     * @returns to Edit Article View
     *
     */

    public function show($id) {
        //Check if Authenticated
        $uid = Session::get('users')->id;
        //Test this functionality
        if (!($uid)) {
            return redirect('/auth/login');
        }
        $userTup = User::find($uid);
        
        

        //Get Article Tuple
        //$arti2 = Article::find($id);
        if (!($article = Article::find($id))) {
            Session::flash('error', 'This Article ID not found in database.');
            return redirect('/article/list/new');
        }

        //Lock Article for Editor
        $addArticle = Article::find($id);
        
         /* Right mgmt start */
        $rightId=8;
        $currentChannelId=$addArticle->channel_id;
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        //Remove comment for Live
        //$addArticle->locked_by = $uid;
        $addArticle->locked_at = date('Y-m-d H:i:s');
        $addArticle->save();

        //Get Author Ids and Names - with label 1, 2, 3
        $arrAuth = DB::table('article_author')
                        ->join('authors', 'authors.author_id', '=', 'article_author.author_id')
                        ->select('article_author.*', 'authors.name')
                        ->where('article_id', '=', $id)->get();

        //Get Category 1,2,3,4
        $acateg1 = ArticleCategory::where('article_id', '=', $id)->get();

        //DB::enableQueryLog();

        $acateg2 = DB::table('article_category')
                        //->join('category','article_category.category_id','=','category.category_id')
                        //->select('article_category.*','category.name')
                        ->where('article_id', '=', $id)->get();
        
        $cateStr = array();
        $acateg = array();
       
        foreach ($acateg2 as $ac) {
            $lable = 'c' . $ac->level;
            $cateStr[$lable] = $ac->category_id;
            //fwrite($asd, " Category Level ::" . $ac->level . " \n");            
            switch ($ac->level) {
                case "1":
                    //echo $ac->category_id; exit;
                    $catlbl = DB::table('category')->where('category_id', '=', $ac->category_id)->get();
                    //print_r($catlbl);exit;
                    if (isset($catlbl[0])) {
                        $acateg[0]['level'] = 1;
                        $acateg[0]['category_id'] = $ac->category_id;
                        $acateg[0]['name'] = $catlbl[0]->name;
                    }
                    break;
                case "2":
                    $catlbl = DB::table('category_two')->where('category_two_id', '=', $ac->category_id)->get();
                    if (isset($catlbl[0])) {
                        $acateg[1]['level'] = 2;
                        $acateg[1]['category_id'] = $ac->category_id;
                        $acateg[1]['name'] = $catlbl[0]->name;
                    }
                    break;
                case "3":
                    $catlbl = DB::table('category_three')->where('category_three_id', '=', $ac->category_id)->get();
                    if (isset($catlbl[0])) {
                        $acateg[2]['level'] = 3;
                        $acateg[2]['category_id'] = $ac->category_id;
                        $acateg[2]['name'] = $catlbl[0]->name;
                    }
                    break;
                case "4":
                    $catlbl = DB::table('category_four')->where('category_four_id', '=', $ac->category_id)->get();
                    if (isset($catlbl[0])) {
                        $acateg[3]['level'] = 4;
                        $acateg[3]['category_id'] = $ac->category_id;
                        $acateg[3]['name'] = $catlbl[0]->name;
                    }
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
       
        $arrTags = ArticleTag::where('article_id', '=', $id)->get();

        $arrTopics = DB::table('article_topics')
                ->join('topics', 'topics.id', '=', 'article_topics.topic_id')
                ->where('article_id', '=', $id)
                ->select('article_topics.*', 'topics.topic')
                ->get();

        $arrPhotos = Photo::where('owned_by', '=', 'article')
                        ->where('owner_id', '=', $id)->get();
        //Get Video
        $arrVideo = Video::where('owned_by', '=', 'article')
                        ->where('owner_id', '=', $id)->get();
        //Get Schedule Time

        $arty = DB::table('articles')
                ->join('article_author', 'article_author.article_id', '=', 'articles.article_id')
                ->join('authors', 'authors.author_id', '=', 'article_author.author_id')
                ->select('articles.*', 'authors.name', 'authors.author_id')
                ->where('articles.article_id', $id)
                ->where('article_author.article_author_rank', '1')
                ->get();

        foreach ($arty as $arty1) {
            //$article = $arty1;
        }

        //fwrite($asd, "EDIT ARTICLE ID::".$article->article_id." \n");

//        $channels = DB::table('channels')
//                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
//                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
//                ->select('channels.*')
//                ->where('rights.label', '=', 'channel')
//                ->where('user_rights.user_id', '=', $uid)
//                ->get();

        $postAs = AuthorType::where('valid', '=', '1')->get();
        $p1 = DB::table('author_type')->where('valid', '1')->lists('label', 'author_type_id');

        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $newstype = DB::table('news_type')->where('valid', '1')->get();
        $category = DB::table('category')->where('channel_id',$currentChannelId)->where('valid', '1')->orderBy('name')->get();

        $magazine = DB::table('magazine')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $event = DB::table('event')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $campaign = DB::table('campaign')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $columns = DB::table('columns')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $tags = json_encode(DB::table('tags')
                        ->select('tags.tags_id as id', 'tags.tag as name')
                        ->join('article_tags', 'tags.tags_id', '=', 'article_tags.tags_id')
                        ->where('tags.valid', '1')
                        ->where('article_tags.valid', '1')
                        ->where('article_tags.article_id', $id)
                        ->get());


        //print_r($tags);exit;
        $photos = DB::table('photos')->where('valid', '1')
                ->where('owned_by', 'article')
                ->where('owner_id', $article->article_id)
                ->get();
    
        return view('articles.edit', compact('article', 'rights', 'channels', 'p1', 'postAs', 'country', 'states', 'newstype', 'category', 'magazine', 'event', 'campaign', 'columns', 'tags', 'photos', 'acateg', 'arrAuth', 'arrTags', 'arrVideo', 'userTup', 'arrTopics'));
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

//        $query = DB::getQueryLog();
//        $lastQuery = end($query);
//        print_r($lastQuery);
//        
        // echo count($rights);exit;
        return $rights;
    }

    public function create() {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
       //echo date('Y-m-d'); 
       //echo date('H:i:s'); 
       //exit;
       
      // exit;
        $uid = Session::get('users')->id;
        
         /* Right mgmt start */
        $rightId=2;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
 
//        $channels = DB::table('channels')
//                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
//                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
//                ->select('channels.*')
//                ->where('rights.label', '=', 'channel')
//                ->where('user_rights.user_id', '=', $uid)
//                ->orderBy('channel')
//                ->get();

        $postAs = AuthorType::where('valid', '=', '1')->orderBy('label')->get();
        $p1 = DB::table('author_type')->where('valid', '1')->lists('label', 'author_type_id');
        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $newstype = DB::table('news_type')->where('valid', '1')->get();
        $category = DB::table('category')->where('channel_id',$currentChannelId)->where('valid', '1')->orderBy('name')->get();

        //fwrite($asd, "\n Channels SELECTED FOR USER ".$channels[1]->channel_id."\n");

        $magazine = DB::table('magazine')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $event = DB::table('event')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $campaign = DB::table('campaign')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        $columns = DB::table('columns')->where('channel_id',$currentChannelId)->where('valid', '1')->get();
        //$tags = DB::table('tags')->where('valid', '1')->get();
      
        return view('articles.create', compact('channels', 'p1', 'postAs', 'country', 'states', 'newstype', 'category', 'magazine', 'event', 'campaign', 'columns', 'tags','currentChannelId'));
    }

    /*
     * Any Update after First Save
     *
     */

    public function update(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        
         /* Right mgmt start */
        $rightId=8;
        $currentChannelId=$request->channel_sel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        // Checking publish permission
        if($request->status=='D' || $request->status == 'SD'){
            if(!$this->rightObj->checkRights($currentChannelId,13))
            return redirect('/dashboard');
        }
        if($request->status=='P'){
            if(!$this->rightObj->checkRights($currentChannelId,12))
            return redirect('/dashboard');
        }
        
        /* Right mgmt end */
       
        $uid = $request->user()->id;
        //fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");
        $article = Article::find($request->id);
        // Add Arr Data to Article Table //
        $article->channel_id = $request->channel_sel;
        $article->author_type = $request->authortype;
        $article->is_columnist = $article->author_type == '4' ? 1 : 0;
        $article->title = $request->title;
        $article->summary = $request->summary;
        $article->description = $request->description;
        $article->country = $request->country;
        $article->state = $request->state;
        $article->news_type = $request->newstype;
        $article->magazine_id = $request->magazine;
        $article->event_id = $request->event;
        if($request->hide_image)
        $article->hide_image= $request->hide_image;   
        if($request->video_Id !=''){
           $article->video_type = 'uploadedvideo' ;
        }else{
            $article->video_type = 'embededvideocode' ;
        }
        $article->video_Id = $request->video_Id;
        $article->canonical_options = $request->canonical_options;
        $article->canonical_url = $request->canonical_url;
        $article->campaign_id = $request->campaign;
        $article->locked_by = 0;
        $article->locked_at = date('Y-m-d H:i:s');

        if ($request->status == 'N') {
            $article->for_homepage = 1;
        } else {
            $article->for_homepage = $request->for_homepage ? 1 : 0;
        }
        $article->important = $request->important ? 1 : 0;
        $article->web_exclusive = $request->web_exclusive ? 1 : 0;

        $article->slug = 'slug';
        //echo $request->status;exit;
        if ($request->status != 'SV')
            $article->status = $request->status;

        //Only for Schedule Article Action
        if ($request->status == 'SD') {
            //echo $request->datepicked; 
            $article->publish_date = trim($request->datepicked)? $request->datepicked:date('Y-m-d');
            $article->publish_time = trim($request->timepicked)?$request->timepicked:date('H:i:s');
            //echo date('Y-m-d'); 
       //echo date('H:i:s');
        } elseif ($request->status == 'P') {
            $article->publish_date = date('Y-m-d');
            $article->publish_time = date('H:i:s');
        }
        $article->update();

        //Get Article_id
        $id = $request->id;

        //- Assign to tertiary tables - Save in Tables -//
        //Save in Author Associative Table - for any other than Online Bureau
        //Get Existing Authors Saved for Article
        $eAuthors = DB::table('article_author')->where('article_id', '=', $id)->get();

        //fwrite($asd, "Step 4.2 In Edit Article POST Function".$id."cscsdcs".count($eAuthors)." \n");

        $arrCollect = array();
        $delArr = array();
        //Check what all Author Ranks exist - if there exist any tuple in article author table
        if (count($eAuthors) > 0) {
            //fwrite($asd, " Here IN IF CONDITION" . count($eAuthors) . " \n");
            foreach ($eAuthors as $each) {
                //fwrite($asd, " FIRST LOOP -----" . $each->author_id . " id \n");
                $arrCollect[$each->article_author_rank][0] = $each->author_id;
                $arrCollect[$each->article_author_rank][1] = $each->article_author_id;
                //fwrite($asd, " AA ID, existing : " . $each->article_author_id . " \n");
            }
            //fwrite($asd, "Count Existing in AUTHOR" . count($arrCollect) . " \n");
        }
        //Save in Author Associative Table - for any other than Online Bureau
        if ($request->authortype != '1') {
            $author_count = 0;
            //For BW Reporters - Multiple
            $rankUpdateArr = array();
            $rankArr = array();

            /////////// ERROR //////////////////
            //Check what Ranks have been replaced, what discarded.
            //$akeys = array_keys($arrCollect)
            for ($i = 1; $i <= 3; $i++) {
                if (array_key_exists($i, $arrCollect)) {
                    $xkey = "author_id" . $i;
                    //fwrite($asd, " Rank Exists: " . $i . " A_id: " . $arrCollect[$i][0] . "At AA ID:" . $arrCollect[$i][1] . " Added A_id: " . $request->$xkey . " \n");
                    //If not same Author selected, add Old to Del Array, else New to New Array
                    if ($arrCollect[$i] != $request->$xkey) {
                        //Update it .. for its been changed, Or delete
                        if ($request->$xkey == '') {
                            //Been removed from Edit Page
                            //fwrite($asd, " Rank has been removed from Edit Page : " . $i . " AA_id: " . $arrCollect[$i][1] . " \n");
                            $delArr[] = $arrCollect[$i][1];
                        }
                        //$delArr[] = $arrCollect[$i];
                        $rankUpdateArr[$xkey][0] = $request->$xkey;
                        $rankUpdateArr[$xkey][1] = $arrCollect[$i][1];
                        //fwrite($asd, " Rank assigned to Update : " . $i . " A_id: " . $arrCollect[$i][0] . " New A_id to be Added: " . $request->$xkey . "\n");
                    } else {
                        //Leave it .. for its unchanged
                        //fwrite($asd, " Rank has Same Author as before.No change : " . $i . " A_id: " . $request->$xkey . " \n");
                        //$rankArr[$xkey] = $request->$xkey;
                    }
                } else {
                    //If Ranks not existing in Old, Add
                    $vkey = "author_id" . $i;
                    //fwrite($asd, " Rank NOT Exists in Old: " . $i . " A_id: " . $request->$vkey . " \n");
                    if ($request->$vkey != '') {
                        //If newly Added Author, Insert it
                        //fwrite($asd, " Rank Add : " . $i . " A_id: " . $request->$vkey . " \n");
                        $rankArr[$i] = $request->$vkey;
                        //$rankArr[$xkey]['id'] = $arrCollect[$i]['id'];
                    }
                }
            }
          
            foreach ($rankUpdateArr as $r => $v) {
                //fwrite($asd, " Article Author ID Being Updated : " . $v[1] . " WIth Author ID:" . $v[0] . " \n");
                $article_authorU = ArticleAuthor::find($v[1]);
                $article_authorU->author_id = $v[0];
                $article_authorU->save();
            }

            foreach ($rankArr as $r => $v) {
                //fwrite($asd, " Author ID Being Added : " . $v . "  \n");
                $article_author = new ArticleAuthor();
                $article_author->article_id = $id;
                $article_author->channel_id = $request->channel_sel;
                $article_author->article_author_rank = $r;
                $article_author->author_id = $v;
                $article_author->valid = '1';

                $article_author->save();
            }
        } else {
            $isalreadyOnlineBurue = 0;
            //Delete All existing
            if (count($eAuthors) > 0) {
                $count = 0;
                foreach ($eAuthors as $r) {
                    //fwrite($asd, " Article Author ID Being Collected to Delete : ".$r->article_author_id."  \n");
                    if ($r->author_id == '1') {
                        $isalreadyOnlineBurue = 1;
                        continue;
                    }
                    $delArr[$count] = $r->article_author_id;
                    $count++;
                }
            }

            // Insertaing new author 
            if ($isalreadyOnlineBurue == 0) {
                $article_author = new ArticleAuthor();
                $article_author->article_id = $id;
                $article_author->channel_id = $request->channel_sel;
                $article_author->article_author_rank = '1';
                $article_author->author_id = '1'; // It's fixed in for all onlien bureau
                $article_author->valid = '1';

                $article_author->save();
            }
        }
        //Delete all discared Article Authors
        if (count($delArr) > 0) {
            foreach ($delArr as $each => $val) {
                //fwrite($asd, " Article Author ID Being Added : ".$val."  \n");
                $old = ArticleAuthor::find($val);
                $old->delete();
            }
        }
        //fclose($asd);
        //Article Topics - Save New: Delete Old
        $arrExistingTopics = DB::table('article_topics')->where('article_id', '=', $id)->get();
        if (count($arrExistingTopics) > 0) {
            foreach ($arrExistingTopics as $eachTopic) {
                //fwrite($asd, " Each Topic Being Deleted : ".$eachTopic->a_topics_id."  \n");
                $delTopic = ArticleTopic::find($eachTopic->a_topics_id);
                $delTopic->delete();
            }
        }
        if ($request->Ltopics) {
            foreach ($request->Ltopics as $key => $value) {
                $article_topics = new ArticleTopic();
                $article_topics->article_id = $id;
                $article_topics->topic_id = $value;
                $article_topics->save();
            }
        }

        //Article Tags - Save New: Delete Old
        $arrExistingTags = DB::table('article_tags')->where('article_id', '=', $id)->get();
        //fwrite($asd, " Tags count found : ".count($arrExistingTags)."  \n");
        if (count($arrExistingTags) > 0) {
            foreach ($arrExistingTags as $eachTag) {
                //fwrite($asd, " Each Tag Being Deleted : ".$eachTag->a_tags_id."  \n");
                $delTag = ArticleTag::find($eachTag->a_tags_id);
                $delTag->delete();
            }
        }
        //Add New Tags
        if ($request->Taglist) {
            //echo $request->Taglist;exit;
            $articleids = explode(',', $request->Taglist);
            $articleids = array_unique($articleids);
            foreach ($articleids as $key => $value) {
                //fwrite($asd, " Each Tag Being Added : ".$value."  \n");
                $article_tags = new ArticleTag();
                $article_tags->article_id = $id;
                $article_tags->tags_id = $value;
                $article_tags->save();
            }
        }
        //Article Category - Save New: Delete Old
        $arrExistingCats = DB::table('article_category')->where('article_id', '=', $id)->get();
        if (count($arrExistingCats) > 0) {
            foreach ($arrExistingCats as $eachCat) {
                //fwrite($asd, " Each Cat Being Deleted : ".$eachCat->a_category_id."  \n");
                $delCat = ArticleCategory::find($eachCat->a_category_id);
                $delCat->delete();
            }
        }
        //Add New Categories
        for ($i = 1; $i <= 4; $i++) {
            $article_category = new ArticleCategory();
            $article_category->article_id = $id;
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            //fwrite($asd, " Each Cat Being Added : ".$request->$label."  \n");
            $article_category->category_id = $request->$label;
            $article_category->level = $i;
            $article_category->save();
        }

        //- Update article_id to respective table -//
        //Video table (article_id)- Save
        $objVideo = Video::where('owner_id', $id)->where('owned_by', 'article')->first();
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
                $objVideo->owned_by = 'article';
                $objVideo->owner_id = $id;
                $objVideo->added_by = $uid;
                $objVideo->added_on = date('Y-m-d');
                $objVideo->save();
            }
        }

//            DB::table('videos')->where('video_id', $request->uploadedVideos)
//                ->update(['owner_id' => $id]);
        //Photos table (article_id)- Save

        foreach($request->get('rimage') as $key => $value){
                    $oldPhoto=Photo::find($key);
                    $articleImage = new Photo();
                    $articleImage->photopath = $oldPhoto->photopath;
                    $articleImage->photo_by = $value;
                    $articleImage->channel_id = $request->channel_sel;
                    $articleImage->owned_by ='article';
                    $articleImage->owner_id =$id;
                    $articleImage->active ='1';
                    $articleImage->created_at = date('Y-m-d H:i:s');
                    $articleImage->updated_at = date('Y-m-d H:i:s');
                    $articleImage->save();
           // echo $key; exit;
        }
        
        $images = explode(',', $request->uploadedImages);

        $s3 = AWS::createClient('s3');
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        foreach ($images as $image) { //echo $request->uploadedImages; exit;
            if (isset($request->photographby[$image])) {
                $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
                $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
                $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagedir') . $image;
                if (@copy($source, $dest)) {


                    $imaged = new Zebra_Image();

                    // indicate a source image
                    $imaged->source_path = $dest;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagethambtdir') . $image;
                    $imaged->preserve_aspect_ratio = false;
                    if ($imaged->resize(90, 63, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagethumbtdir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }

                    //$imaged->source_path = $dest;
                    $imaged->preserve_aspect_ratio = true;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagemediumdir') . $image;
                    if ($imaged->resize(349, 219, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagemediumdir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }
                    //$imaged->source_path = $dest;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagelargedir') . $image;
                    if ($imaged->resize(476, 257, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagelargedir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }
                    //$imaged->source_path = $dest;
                    //$imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimageextralargedir') . $image;
                    $result = $s3->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimageextralargedir') . $image,
                        'SourceFile' => $imaged->source_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->source_path);
                    }

//                if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
//                   
//                }

                    unlink($source);
                    unlink($source_thumb);
                    //unlink($dest);
                    $articleImage = new Photo();
                    $articleImage->photopath = $image;
                    $articleImage->imagefullPath = '';
                    $articleImage->photo_by = $request->photographby[$image];
                    $articleImage->channel_id = $request->channel_sel;
                    $articleImage->owned_by = 'article';
                    $articleImage->owner_id = $id;
                    $articleImage->active = '1';
                    $articleImage->created_at = date('Y-m-d H:i:s');
                    $articleImage->updated_at = date('Y-m-d H:i:s');
                    $articleImage->save();
                }
            }
        }


        if ($article->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published?channel='.$currentChannelId);
        } elseif ($article->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new?channel='.$currentChannelId);
        } elseif ($article->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted?channel='.$currentChannelId);
        } elseif ($article->status == 'SD') {
            Session::flash('message', 'Your Article has been Scheduled successfully.');
            return redirect('/article/list/scheduled?channel='.$currentChannelId);
        } else {
            Session::flash('message', 'Your Article has been saved in your draftsuccessfully.');
            return redirect('/article/list/drafts?channel='.$currentChannelId);
        }

        //return Redirect::to('/dashboard');
        //return view('/dashboard');
    }

    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
         /* Right mgmt start */
        $rightId=2;
        $currentChannelId=$request->channel_sel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
       
        $uid = $request->user()->id;

        //fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");            

        $article = new Article();

        // Add Arr Data to Article Table //
        $article->channel_id = $request->channel_sel;
        $article->user_id = $uid;
        $article->author_type = $request->authortype;
        $article->is_columnist = $article->author_type == '4' ? 1 : 0;
        $article->title = $request->title;
        $article->summary = $request->summary;
        $article->description = $request->description;
        $article->country = $request->country;
        $article->state = $request->state;
        $article->news_type = $request->newstype;
        $article->magazine_id = $request->magazine;
        $article->event_id = $request->event;
        $article->canonical_options = $request->canonical_options;
        $article->canonical_url = $request->canonical_url;
        $article->video_Id = $request->video_Id;
        if($request->hide_image)
        $article->hide_image= $request->hide_image;
        if($request->video_Id !=''){
           $article->video_type = 'uploadedvideo' ;
        }else{
            $article->video_type = 'embededvideocode' ;
        }
        $article->campaign_id = $request->campaign;
        //$article->publish_date = 0;//$request->datepicked;
        //$article->publish_time = 0;//$request->timepicked;
        if ($request->status == 'N') {
            $article->for_homepage = 1;
        } else {
            $article->for_homepage = $request->for_homepage ? 1 : 0;
        }
        //Only for Schedule Article Action
        if ($request->status == 'SD') {
            //echo $request->datepicked; 
            //$article->publish_date = $request->datepicked;
            //$article->publish_time = $request->timepicked;
            $article->publish_date = trim($request->datepicked)?$request->datepicked:date('Y-m-d');
            $article->publish_time = trim($request->timepicked)?$request->timepicked:date('H:i:s');
        } elseif ($request->status == 'P') {
            $article->publish_date = date('Y-m-d');
            $article->publish_time = date('H:i:s');
        }

        $article->important = $request->important ? 1 : 0;
        $article->web_exclusive = $request->web_exclusive ? 1 : 0;
        
        $article->slug = 'slug';
        $article->status = $request->status;
//        echo '<pre>';
//        print_r($request->all());exit;
        $article->save();

        //Get Article_id
        $id = $article->article_id;

        //- Assign to tertiary tables - Save in Tables -//
        //Save in Author Associative Table - for any other than Online Bureau
        if ($request->authortype != '1') {
            $author_count = 0;
            //For BW Reporters - Multiple
            if ($request->authortype == '2' and ( $request->author_id2 or $request->author_id3)) {
                if ($request->author_id2 && $request->author_id3) {
                    $author_count = 3;
                    $authorid = array($request->author_id1, $request->author_id2, $request->author_id3);
                    $authorRank = array('1', '2', '3');
                } elseif ($request->author_id2) {
                    $author_count = 2;
                    $authorid = array($request->author_id1, $request->author_id2);
                    $authorRank = array('1', '2');
                } elseif ($request->author_id3) {
                    $author_count = 2;
                    $authorid = array($request->author_id1, $request->author_id3);
                    $authorRank = array('1', '3');
                }
            } else {
                $author_count = 1;
                $authorid = array($request->author_id1);
                $authorRank = array('1');
            }
            //echo "aCount: ".$author_count;
            for ($i = 0; $i < $author_count; $i++) {
                $article_author = new ArticleAuthor();
                $article_author->article_id = $id;
                $article_author->channel_id = $request->channel_sel;
                $article_author->article_author_rank = $authorRank[$i];
                $article_author->author_id = $authorid[$i];
                $article_author->valid = '1';

                $article_author->save();
            }
        } else {// Assignig static author if author type is online bureau
            $article_author = new ArticleAuthor();
            $article_author->article_id = $id;
            $article_author->channel_id = $request->channel_sel;
            $article_author->article_author_rank = '1';
            $article_author->author_id = '1'; // It's fixed in for all onlien bureau
            $article_author->valid = '1';

            $article_author->save();
        }
        //Article Topics - Save
        if ($request->Ltopics) {
            foreach ($request->Ltopics as $key => $value) {
                $article_topics = new ArticleTopic();
                $article_topics->article_id = $id;
                $article_topics->topic_id = $value;
                $article_topics->save();
            }
        }
        //Article Tags - Save
        if ($request->Taglist) {
            $articleids = explode(',', $request->Taglist);
            $articleids = array_unique($articleids);
            foreach ($articleids as $key => $value) {
                $article_tags = new ArticleTag();
                $article_tags->article_id = $id;
                $article_tags->tags_id = $value;
                $article_tags->save();
            }
        }
        //Article Category - Save
        for ($i = 1; $i <= 4; $i++) {
            $article_category = new ArticleCategory();
            $article_category->article_id = $id;
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            $article_category->category_id = $request->$label;
            $article_category->level = $i;
            $article_category->save();
        }

        //- Update article_id to respective table -//
        //Video table (article_id)- Save
        if (trim($request->videoTitle) || trim($request->videoCode) || trim($request->videoSource) || trim($request->videoURL)) {
            $objVideo = new Video();
            $objVideo->title = $request->videoTitle;
            $objVideo->code = $request->videoCode;
            $objVideo->source = $request->videoSource;
            $objVideo->url = $request->videoURL;
            $objVideo->channel_id = $request->channel_sel;
            $objVideo->owned_by = 'article';
            $objVideo->owner_id = $id;
            $objVideo->added_by = $uid;
            $objVideo->added_on = date('Y-m-d');
            $objVideo->save();
        }

//            DB::table('videos')->where('video_id', $request->uploadedVideos)
//                ->update(['owner_id' => $id]);
        //Photos table (article_id + channel_id)- Save
        foreach($request->get('rimage') as $key => $value){
                    $oldPhoto=Photo::find($key);
                    $articleImage = new Photo();
                    $articleImage->photopath = $oldPhoto->photopath;
                    $articleImage->photo_by = $value;
                    $articleImage->channel_id = $request->channel_sel;
                    $articleImage->owned_by ='article';
                    $articleImage->owner_id =$id;
                    $articleImage->active ='1';
                    $articleImage->created_at = date('Y-m-d H:i:s');
                    $articleImage->updated_at = date('Y-m-d H:i:s');
                    $articleImage->save();
           // echo $key; exit;
        }
        
        $images = explode(',', $request->uploadedImages);
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");

        $s3 = AWS::createClient('s3');
        foreach ($images as $image) { //echo 'foreach--';
            if (isset($request->photographby[$image])) {

                $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
                $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
                $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagedir') . $image;
                //echo $source; echo '<br>'; echo $dest;

                if (@copy($source, $dest)) { //echo 'copied--';
                    $imaged = new Zebra_Image();

                    // indicate a source image
                    $imaged->source_path = $dest;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagethambtdir') . $image;
                    $imaged->preserve_aspect_ratio = false;
                    if ($imaged->resize(90, 63, ZEBRA_IMAGE_BOXED, -1)) { //echo 'resized--';
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagethumbtdir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }
                    //$imaged->source_path = $dest;
                    $imaged->preserve_aspect_ratio = true;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagemediumdir') . $image;
                    if ($imaged->resize(349, 219, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagemediumdir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }
                    //$imaged->source_path = $dest;
                    $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagelargedir') . $image;
                    if ($imaged->resize(476, 257, ZEBRA_IMAGE_BOXED, -1)) {
                        $result = $s3->putObject(array(
                            'ACL' => 'public-read',
                            'Bucket' => config('constants.awbucket'),
                            'Key' => config('constants.awarticleimagelargedir') . $image,
                            'SourceFile' => $imaged->target_path,
                        ));
                        if ($result['@metadata']['statusCode'] == 200) {
                            unlink($imaged->target_path);
                        }
                    }
                    //$imaged->source_path = $dest;
                    //$imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimageextralargedir') . $image;
                    //if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL' => 'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimageextralargedir') . $image,
                        'SourceFile' => $imaged->source_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->source_path);
                    }
                    //}
                    // echo 'before unlink'; exit;
                    unlink($source);
                    unlink($source_thumb);
                    //unlink($dest);
                    $articleImage = new Photo();
                    $articleImage->photopath = $image;
                    $articleImage->imagefullPath = '';
                    $articleImage->photo_by = $request->photographby[$image];
                    $articleImage->channel_id = $request->channel_sel;
                    $articleImage->owned_by = 'article';
                    $articleImage->owner_id = $id;
                    $articleImage->active = '1';
                    $articleImage->created_at = date('Y-m-d H:i:s');
                    $articleImage->updated_at = date('Y-m-d H:i:s');
                    $articleImage->save();
                }
            }
        }

        //}
        //Add article_id to Relational Tables;
        //     exit;        
        //fwrite($asd, "Step 3.1 In Article POST Function END ING \n");        
        //If has been Saved by Editor
        if ($request->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published?channel='.$currentChannelId);
        }
        if ($request->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new?channel='.$currentChannelId);
        }
        if ($request->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted?channel='.$currentChannelId);
        }
        if ($request->status == 'S') {
            Session::flash('message', 'Your Article has been saved successfully in - My Drafts.');
            return redirect('/article/list/drafts?channel='.$currentChannelId);
        }
       if ($article->status == 'SD') {
            Session::flash('message', 'Your Article has been Scheduled successfully.');
            return redirect('/article/list/scheduled?channel='.$currentChannelId);
        }

        return redirect('/article/list/new?channel='.$currentChannelId);

        //return redirect('/dashboard');
        //return Redirect::to('/dashboard');
        //return view('/dashboard');
    }

    /**
     * Adding Author Details to Author Table by Author Controller
     *
     * @param Request $request
     * @internal param $data
     */
    public function postAuthor() {
        $data = Request;
        //Add Author fields sent to Author Table
        //AuthorsController->AuthorsController::store($request);
        $p = sizeof($data);
        //  $p = $data;
        //print_r($_POST);
        //$l = fopen('/home/sudipta/check.log','w+');
        //fwrite($l," SIZEOF ".$p);
        //Author::create($request);
        //AuthorsController->store($request);
        return;
    }

    public function destroy() {
        
        if (!Session::has('users')) {
            return 'Please login first.';
        }
        
         /* Right mgmt start */
        $rightId=13;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId)){
            return 'You are not authorized to access';
        }   
        /* Right mgmt end */
        

        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteAl = Article::find($d);
            if ($deleteAl->status != 'S') {
                $deleteAl->status = 'D';
                $deleteAl->save();
            } else {
                // Delete releted content before deleting article. 
                $deleteAl->delete();
            }
        }
        return 'success';
    }

    public function publishBulk() {
        
        if (!Session::has('users')) {
            return 'Please login first.';
        }
        /* Right mgmt start */
        $rightId=12;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return 'You are not authorized to access';
        /* Right mgmt end */
        
        
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteAl = Article::find($d);
            $deleteAl->status = 'P';
            $deleteAl->publish_date = date('Y-m-d');
            $deleteAl->publish_time = date('H:i:s');
            $deleteAl->save();
        }
        return 'success';
    }

    public function publishScheduledArticle() {
        if (!Session::has('users')) {
            return 'Please login first.';
        }
        
        /* Right mgmt start */
        $rightId=12;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return 'You are not authorized to access';
        /* Right mgmt end */
        
        $articles = DB::table('articles')
                ->where('status', 'SD')
                ->where('publish_date', '<=', date('Y-m-d'))
                ->where('publish_time', '<=', date('h:i:s'))
                ->get();
        //echo count($articles);exit;
        foreach ($articles as $article) {
            $updatearticle = Article::find($article->article_id);
            $updatearticle->status = 'P';
            $updatearticle->update();
        }
        return 'success';
    }
    
   function relatedImage(Request $request){//13803,13546
     //$query="select *,MATCH(tag) AGAINST ('".$request->search_key."') as score from tags where MATCH(tag) AGAINST ('".$request->search_key."') order by updated_at desc,score desc limit 5";
       $total=25;
       $query="select *,count(*) as cs,MATCH(tag) AGAINST ('".$request->search_key."') as score from tags join (select * from article_tags order by updated_at desc ) as article_tags on tags.tags_id=article_tags.tags_id where MATCH(tag) AGAINST ('".$request->search_key."') group by tags.tags_id order by article_tags.updated_at desc,score desc limit 5";
       $tags=DB::select($query);
       $related_images=array();
       $imgids=array();
       $cond='';
      // print_r($tags);
       usort($tags,array($this,'compareByCount'));
       //print_r($tags);exit;
     // print_r($tags);
       $minlimit=ceil($total/count($tags));
       $maxlimit=ceil($total/count($tags));
      //echo $limit;exit;
      // $rtags=array();
       foreach($tags as $tag){
           if(count($imgids)>0){
               $cond=' and photo_id not in ('.implode(',',$imgids).')';
           }
           //echo $maxlimit;
          $imagequery="select photo_id,photopath,photo_by from photos where valid='1' and photopath!='' and owned_by='article' $cond and owner_id in(SELECT articles.article_id FROM `articles` inner join article_tags on articles.article_id=article_tags.article_id WHERE  article_tags.tags_id=".$tag->tags_id." order by article_tags.updated_at desc) order by updated_at desc";
          $images=DB::select($imagequery);
          if(count($images)<$maxlimit){
              $maxlimit=$maxlimit+($minlimit-count($images));
          }else{
              $maxlimit=$minlimit;
          }
            foreach($images as $image){
                $imgids[]=$image->photo_id;
             $related_images[]=array('image_url'=>config('constants.awsbaseurl').config('constants.awarticleimagethumbtdir').$image->photopath,'image_id'=>$image->photo_id,'tag_name'=>$tag->tag,'tag_id'=>$tag->tags_id,'photo_by'=>$image->photo_by,'image_name'=>$image->photopath);
          }
          
        }
       
       return json_encode($related_images);
   }
  public static function compareByCount($a, $b) {
    return strcmp($a->cs, $b->cs);
  }

}
