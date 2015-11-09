<?php

namespace App\Http\Controllers;

use App\ArticleAuthor;
use App\ArticleCategory;
use App\ArticleTag;
use App\ArticleTopic;
use App\Category;
use App\Photo;
use App\Right;
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

    public function __construct() {
        //$this->middleware('auth', [only=>'create']);
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, " Step 1-- ArticleController\n");
        //fclose($asd);
        //$this->middleware('auth');
    }

    public function index($option) {
//        echo '<pre>';
//        print_r(json_decode('[{"22":{"tags_id":"202","tag":"tagtest","valid":"1","created_at":"2015-09-10 16:36:11","updated_at":"2015-09-10
// 16:36:11"}},{"23":{"tags_id":"203","tag":"tet","valid":"1","created_at":"2015-09-10 16:36:11","updated_at"
//:"2015-09-10 16:36:11"}},{"24":{"tags_id":"204","tag":"fdfflkj","valid":"1","created_at":"2015-09-10
// 16:36:12","updated_at":"2015-09-10 16:36:12"}},{"25":{"tags_id":"205","tag":"hkjhfkdff","valid":"1"
//,"created_at":"2015-09-10 16:36:12","updated_at":"2015-09-10 16:36:12"}}]'));exit;
        //dd($option);
//        dd('Not here');
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch ($option) {
            case "drafts":
                $status = 'S';
                $page = "drafts";
                $rightLabel = "drafts";
                break;
            case "new":
                $status = 'N';
                $rightLabel = "newArticles";
                break;
            case "scheduled":
                $status = 'SD';
                $rightLabel = "scheduledArticles";
                break;
            case "published":
                $status = 'P';
                $rightLabel = "publishedArticles";
                break;
            case "deleted":
                $status = 'D';
                $rightLabel = "deletedArticles";
                break;
        }
        //Get User Rights
        $arrRights = ArticlesController::getRights($uid);
        //To get channels
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $cArr = array();
        foreach ($arrRights as $eachRight) {
            if ($eachRight->label == 'channel') {
                $cArr[] = $eachRight->pagepath;
                //fwrite($asd, " CHANNEL SELECTED ::" . $uid . " user_id :" . $eachRight->pagepath . " \n");
                //return view('articles.'.$option, compact('articles','editor'));
            }
        }
        $cArr = array_unique($cArr);

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
            // print_r($cArr);exit;
            //foreach( as $chnl) {
            DB::enableQueryLog();
            $condition = '';

            $q = DB::table('articles')
                    ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                    ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id');
            if ($option == 'new') {
                $q->join('users', 'articles.user_id', '=', 'users.id');
                $q->select(DB::raw('articles.article_id,articles.title,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by,users.name as username'));
            } else {
                $q->select(DB::raw('articles.article_id,articles.title,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by'));
            }
            $q->whereIn('articles.channel_id', $cArr)
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
            $q->orderBy('articles.publish_date', 'desc');
            $q->orderBy('articles.publish_time', 'desc');
            $q->orderBy('articles.updated_at', 'desc');
            // $articlesE[$i]
            $articles = $q->groupBy('articles.article_id')->paginate(config('constants.recordperpage'));
            /*
              ->select('articles.article_id', 'articles.title', 'articles.article_id', 'articles.publish_date', 'articles.publish_time',
              'group_concat(authors.name)', 'articles.channel_id', 'articles.locked_by')

             */
//                 $query = DB::getQueryLog();
//        $lastQuery = end($query);
//        print_r($lastQuery);exit;
            //$articles = array_merge($articles,$articlesE[$i]);
            //$i++;
            //fwrite($asd, " STATUS SELECTED ::" . $status . " channel_id :" . $chnl . " \n");
            //}
            /*
              $articles = DB::table('articles')
              ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
              ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id')
              ->join('rights', 'articles.channel_id', '=', 'rights.pagepath')
              ->join('user_rights', 'rights.rights_id', '=', 'user_rights.rights_id')
              ->select('articles.article_id', 'articles.title', 'articles.article_id', 'articles.publish_date', 'articles.publish_time',
              'authors.name', 'articles.channel_id', 'articles.locked_by')
              ->where('rights.label', '=', 'channel')
              ->where('user_rights.user_id', '=', $uid)
              ->where('status', $status)
              ->get();
             */
            $editor = DB::table('users')
                    ->join('articles', 'users.id', '=', 'articles.locked_by')
                    ->select('users.name', 'articles.article_id')
                    ->where('status', $status)
                    ->get();
        }
        //->paginate(9);
        //fclose($asd);
        //Returns JSON
        //return $articles;
        foreach ($arrRights as $eachRight) {

            if ($rightLabel == $eachRight->label) {
                return view('articles.' . $option, compact('articles', 'editor'));
            }
        }

        return redirect('/dashboard');
        //return view('articles.index', compact('articles'));
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
     * Edit Article Display Process
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

        //Check if has Access to Article ID
        $userTup = User::find($uid);

        //Get User Rights
        $rights = ArticlesController::getRights($uid, 8);

        //Get Article Tuple
        //$arti2 = Article::find($id);
        if (!($article = Article::find($id))) {
            Session::flash('error', 'This Article ID not found in database.');
            return redirect('/article/list/new');
        }


        /* $asd = fopen("/home/sudipta/log.log", 'a+');
          fwrite($asd, " ONE :Has no right ::" . $uid . " user_id :" . $article->user_id . " \n");
          fclose($asd);
         */
        //Check if Article has Edit Page - Access
        //If Not - Flash Message & redirect
        $hasEditAccess = ArticlesController::hasRightOnArticle($uid, $article->user_id, $rights);
        if ($hasEditAccess == 0) {
            Session::flash('error', 'You do not have access on this Article.');
            return redirect('/article/list/new');
        }

        //Lock Article for Editor
        $addArticle = Article::find($id);
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
        //$queries = DB::getQueryLog();
        //$last_query = end($queries);
        // print_r($last_query);
        // exit;
        //$cateStr = "{";
        $cateStr = array();
        $acateg = array();
        //echo count($acateg2);exit;
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, " COUNT OF ARR ::" . count($acateg) . " \n");
        //fclose($asd);

        foreach ($acateg2 as $ac) {
            $lable = 'c' . $ac->level;
            $cateStr[$lable] = $ac->category_id;
            //fwrite($asd, " Category Level ::" . $ac->level . " \n");            
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
            //$asd = fopen("/home/sudipta/log.log", 'a+');
            //fwrite($asd, " Category IDs ::".$ac->category_id." Str: \n");            
            //$cateStr.="\"c$ac->level\":\"$ac->category_id\",";
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
        //print_r($acateg);
        //exit;
        //Get Tags, Topics, .. array
        $arrTags = ArticleTag::where('article_id', '=', $id)->get();

        //Topics
        $arrTopics = DB::table('article_topics')
                ->join('topics', 'topics.id', '=', 'article_topics.topic_id')
                ->where('article_id', '=', $id)
                ->select('article_topics.*', 'topics.topic')
                ->get();

        //fwrite($asd, " Tags Arr ::".var_dump($arrAuth)." Str: \n");
        //Get Images -
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

        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $uid)
                ->get();

        $postAs = AuthorType::where('valid', '=', '1')->get();
        $p1 = DB::table('author_type')->where('valid', '1')->lists('label', 'author_type_id');

        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $newstype = DB::table('news_type')->where('valid', '1')->get();
        $category = DB::table('category')->where('valid', '1')->orderBy('name')->get();

        $magazine = DB::table('magazine')->where('valid', '1')->get();
        $event = DB::table('event')->where('valid', '1')->get();
        $campaign = DB::table('campaign')->where('valid', '1')->get();
        $columns = DB::table('columns')->where('valid', '1')->get();
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

        //dd($article);
        /*
          if (is_null($article)){
          abort(404);
          } */
        //return $articles;
        //echo '<pre>';
        // print_r($article);exit;
        //print_r($arrAuth);exit;
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
        //dd('here');
        //$uid = Auth::user();
        $uid = Session::get('users')->id;
        //$uid = $this->session()->get('id');
        //$uid = $request->user()->id;
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "\n Step 2.8 In Article CREATE".$uid."\n");
        //$channels = Channel::where('valid','=','1')->get();
        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $uid)
                ->orderBy('channel')
                ->get();

        $postAs = AuthorType::where('valid', '=', '1')->orderBy('label')->get();
        $p1 = DB::table('author_type')->where('valid', '1')->lists('label', 'author_type_id');
        $rights = ArticlesController::getRights($uid, 1); /*
          $rights = DB::table('rights')
          ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
          ->where('user_rights.user_id','=',$uid)
          ->get();
         */
        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $newstype = DB::table('news_type')->where('valid', '1')->get();
        $category = DB::table('category')->where('valid', '1')->orderBy('name')->get();

        //fwrite($asd, "\n Channels SELECTED FOR USER ".$channels[1]->channel_id."\n");

        $magazine = DB::table('magazine')->where('valid', '1')->get();
        $event = DB::table('event')->where('valid', '1')->get();
        $campaign = DB::table('campaign')->where('valid', '1')->get();
        $columns = DB::table('columns')->where('valid', '1')->get();
        $tags = DB::table('tags')->where('valid', '1')->get();
        //$tags = DB::table('tags')->where('valid','1')->lists('tag','tags_id');
        //$photos = DB::table('photos')->where('valid', '1')->get();
        //$newstype = NewsType::where('valid','=','1')->get();
        //return view('layouts.dashboard2');
        //return view('articles.index', compact('articles'));
        return view('articles.create', compact('channels', 'p1', 'postAs', 'country', 'states', 'newstype', 'category', 'magazine', 'event', 'campaign', 'columns', 'tags', 'rights'));
    }

    /*
     * Any Update after First Save
     *
     */

    public function update(Request $request) {

        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "Step 4.1 In Edit Article POST Function \n");
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
//print_r($_POST);
        //exit;
        //Session's User Id
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
            $article->publish_date = $request->datepicked;
            $article->publish_time = $request->timepicked;
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
            //if(($arrCollect['1']) and ($arrCollect['1'] != $request->author_id1)){$delArr[] = $arrCollect['1'];}else{$rankArr['1'] = $request->author_id1;}
            //if(($arrCollect['2']) and ($arrCollect['2'] != $request->author_id2)){$delArr[] = $arrCollect['2'];}else{$rankArr['2'] = $request->author_id2;}
            //if(($arrCollect['3']) and ($arrCollect['3'] != $request->author_id3)){$delArr[] = $arrCollect['3'];}else{$rankArr['3'] = $request->author_id3;}
            //fclose($asd);
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

        $images = explode(',', $request->uploadedImages);
        $s3 = AWS::createClient('s3');
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        foreach ($images as $image) { //echo $request->uploadedImages; exit;
            $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagedir') . $image;
            if (@copy($source, $dest)) {


                $imaged = new Zebra_Image();

                // indicate a source image
                $imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagethambtdir') . $image;
               
                if ($imaged->resize(90, 76, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimagethumbtdir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                  
                }
               
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagemediumdir') . $image;
                if ($imaged->resize(160, 90, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
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
                if ($imaged->resize(500, 270, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimagelargedir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimageextralargedir') . $image;
                if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimageextralargedir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }

                unlink($source);
                unlink($source_thumb);
                unlink($dest);
                $articleImage = new Photo();
                $articleImage->photopath = $image;
                $articleImage->imagefullPath = '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            }

        }


        if ($article->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published');
        } elseif ($article->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new');
        } elseif ($article->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted');
        } elseif ($article->status == 'SD') {
            Session::flash('message', 'Your Article has been Scheduled successfully.');
            return redirect('/article/list/scheduled');
        } else {
            Session::flash('message', 'Your Article has been saved in your draftsuccessfully.');
            return redirect('/article/list/drafts');
        }

        //return Redirect::to('/dashboard');
        //return view('/dashboard');
    }

    public function store(Request $request) {
                //echo '<br>';
        //echo '<pre>';
        //print_r($request->all());exit;
        //$d = new Request;
        //echo $HTTP_POST_VARS;
        //print_r($_POST);
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
        //if($request->status != 'S') {
        //Session's User Id
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
        $article->campaign_id = $request->campaign;
        //$article->publish_date = 0;//$request->datepicked;
        //$article->publish_time = 0;//$request->timepicked;
        if ($request->status == 'N') {
            $article->for_homepage = 1;
        } else {
            $article->for_homepage = $request->for_homepage ? 1 : 0;
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
      
        $images = explode(',', $request->uploadedImages);
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        $s3 = AWS::createClient('s3');
        foreach ($images as $image) { //echo 'foreach--';
            $source = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $image;
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $dest = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagedir') . $image;
            //echo $source; echo '<br>'; echo $dest;
            
            if (@copy($source, $dest)) { //echo 'copied--';

                $imaged = new Zebra_Image();

                // indicate a source image
                $imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagethambtdir') . $image;

                if ($imaged->resize(90, 76, ZEBRA_IMAGE_BOXED, -1)) { //echo 'resized--';
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimagethumbtdir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimagemediumdir') . $image;
                if ($imaged->resize(160, 90, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
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
                if ($imaged->resize(500, 270, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(                        
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimagelargedir') . $image,
                        'SourceFile' => $imaged->target_path,
                    ));
                    if ($result['@metadata']['statusCode'] == 200) {
                        unlink($imaged->target_path);
                    }
                }
                //$imaged->source_path = $dest;
                $imaged->target_path = $_SERVER['DOCUMENT_ROOT'] . '/' . config('constants.articleimageextralargedir') . $image;
                if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)) {
                    $result = $s3->putObject(array(
                        'ACL'=>'public-read',
                        'Bucket' => config('constants.awbucket'),
                        'Key' => config('constants.awarticleimageextralargedir') . $image,
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
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            }

        }

        //}
        //Add article_id to Relational Tables;
        //     exit;        
        //fwrite($asd, "Step 3.1 In Article POST Function END ING \n");        
        //If has been Saved by Editor
        if ($request->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published');
        }
        if ($request->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new');
        }
        if ($request->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted');
        }
        if ($request->status == 'S') {
            Session::flash('message', 'Your Article has been saved successfully in - My Drafts.');
            return redirect('/article/list/drafts');
        }

        return redirect('/article/list/new');

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
            $deleteAl = Article::find($d);
            if ($deleteAl->status != 'S') {
                $deleteAl->status = 'D';
                $deleteAl->save();
            } else {
                // Delete releted content before deleting article. 
                $deleteAl->delete();
            }
        }
        return;
    }

    public function publishBulk() {
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
        return;
    }

    public function publishScheduledArticle() {
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
    }
    
    

}
