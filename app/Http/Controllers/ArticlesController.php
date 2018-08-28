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
use App\Trending;
use Session;
use App\Article;
use App\QuickByte;
use App\Album;
use App\Channel;
use App\AuthorType;
use App\Country;
use App\State;
use App\Author;
use App\NewsType;
use App\MasterVideo;
use App\PhotoTag;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use App\Classes\UploadHandler;
use App\Classes\FileTransfer;
use App\Classes\Zebra_Image;
use App\Classes\GeneralFunctions;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class ArticlesController extends Controller {

    private $rightObj;

    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }

    public function index($option) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
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
                $rightId = 11;
                break;
            case "scheduled":
                $status = 'SD';
                $rightLabel = "scheduledArticles";
                $rightId = 15;
                break;
            case "published":
                $status = 'P';
                $rightLabel = "publishedArticles";
                $rightId = 16;
                break;
            case "deleted":
                $status = 'D';
                $rightLabel = "deletedArticles";
                $rightId = 17;
                break;
        }
        //echo 'test'; exit;
        /* Right mgmt start */
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
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
                    ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id')
                    ->Leftjoin('users as locker','articles.locked_by','=','locker.id');
            if ($option == 'new') {
                $q->join('users', 'articles.user_id', '=', 'users.id');
                $q->select(DB::raw('articles.article_id,articles.title,articles.created_at,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.auto_published,articles.channel_id,articles.locked_by,users.name as username,locker.name as locker_name,articles.locked_at'));
                $q->orderBy('articles.created_at', 'desc');
            } else {
                $q->select(DB::raw('articles.article_id,articles.title,articles.auto_published,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by,locker.name as locker_name,articles.locked_at'));
                if ($option == 'scheduled') {
                    $q->orderBy('articles.updated_at', 'desc');
                } else {
                    $q->orderBy('articles.publish_date', 'desc');
                    $q->orderBy('articles.publish_time', 'desc');
                }
            }
            $q->where('articles.channel_id', $currentChannelId)
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
        return view('articles.' . $option, compact('articles', 'editor', 'channels', 'currentChannelId'));
    }

    function imageUpload(){

         
        $arg['script_url'] = url('article/image/upload');
        $upload_handler = new UploadHandler($arg);
        
    }

    function imageEdit(Request $request) {  
        $tags=array();
        $photo = Photo::find($request->id);
        $article = '';
        if ($photo->owned_by == 'article') {
            $article = Article::find($photo->owner_id);
        }
        
        if ($photo->owned_by == 'quickbyte') {
            //DB::enableQueryLog();
            $tags =Db::table('photo_tags')
                    ->select('tags.tags_id as id','tags.tag as name')
                    ->join('tags','photo_tags.tag_id','=','tags.tags_id')
                    ->where('photo_tags.photo_id','=',$request->id)
                    ->get();
            //dd(DB::getQueryLog());
            //exit;
        }
        $tags=json_encode($tags);
        
        return view('layouts.imageEdit', compact('photo', 'article','tags'));
    }

    function storeImageDetail(Request $request) {
        //echo $request->detail;exit;
        parse_str($request->detail);
        $photo = Photo::find($photo_id);
        if ($photo->owned_by == 'article') {
            $article = Article::find($photo->owner_id);
            $photo->title = $imagetitlep;
            $photo->photo_by = $imagebyp;
            //social_image_popup
            $return = ' <td>
                            <img alt="article" src="' . config('constants.awsbaseurl') . config('constants.awarticleimagethumbtdir') . $photo->photopath . '">
                        </td>
                        <td>' . $photo->title . ' / ' . $photo->photo_by . '</td>
                <input type="hidden" id="' . $photo->photo_id . '" name="deleteImagel">
                <td class="center"><button class="btn btn-mini btn-danger" id="deleteImage" name="' . $photo->photo_id . '" onclick="$(this).MessageBox(' . $photo->photo_id . ')" type="button">Dump</button>
                    <button class="btn btn-mini btn-edit" id="deleteImage" name="image' . $photo->photo_id . '" onclick="editImageDetail(' . $photo->photo_id . ',\'article\')" type="button">Edit</button>
                    <img style="width:20%; display:block; margin-left:15px;display:none;" alt="loader" src="' . asset('images/photon/preloader/76.gif') . '"></td>
               ';
            $updatearray = array('updated_at' => date('Y:m:d H:i:s'));
            if (isset($social_image_popup)) {
                $updatearray['social_image'] = $social_image_popup;
            }elseif($article->social_image==$photo->photopath){
                 $updatearray['social_image']='';
            }
            // print_r($updatearray); exit;
            DB::table('articles')
                    ->where('article_id', $photo->owner_id)
                    ->update($updatearray);
        } elseif ($photo->owned_by == 'quickbyte') {
            $photo->title = $imagetitlep;
            $photo->photo_by = $imagebyp;
            $photo->description = $descriptionp;
            
            
            
            $oldTags=json_decode($prepop_tag);
           // print_r($oldTags); exit;
            $oldArray=array();
            foreach($oldTags as $tag){
                $oldArray[]=$tag->id;
            }
            $newArray=explode(',',$image_tags);
            
            $to_delete=array_diff($oldArray,$newArray);
            $to_insert=array_diff($newArray,$oldArray);
            
            PhotoTag::whereIn('tag_id',$to_delete)->where('photo_id','=',$photo_id)->delete();   
            
            foreach($to_insert as $tag_id){
                $photoTag=new PhotoTag();
                $photoTag->photo_id=$photo_id;
                $photoTag->tag_id=$tag_id;
                $photoTag->save();
            }   
            $return = '
                                            <td width="20%">
                                                <img style="width:40%;" alt="user" src="' . config('constants.awsbaseurl') . config('constants.awquickbytesimagethumbtdir') . $photo->photopath . '">
                                            </td>
                                            <td width="20%">' . $photo->title . '</td>
                                             <td width="30%" class="tdimagedesc">' . $photo->description . '</td>
                                            <td width="15%">' . $photo->photo_by . '</td>
                                    <input type="hidden" id="' . $photo->photo_id . '" name="deleteImagel">
                                    <td with="15%" class="center">
                                        <button class="btn btn-mini btn-danger" id="deleteImage" name="' . $photo->photo_id . '" onclick="$(this).MessageBox(' . $photo->photo_id . ')" type="button">Dump</button>
                                        <button class="btn btn-mini btn-edit" id="deleteImage" name="image' . $photo->photo_id . '" onclick="editImageDetail(' . $photo->photo_id . ',\'quickbyte\')" type="button">Edit</button>
                                        <img style="width:20%; display:block; margin-left:15px;display:none;" alt="loader" src="' . asset('images/photon/preloader/76.gif') . '"></td>
                                   ';

            DB::table('quickbyte')
                    ->where('id', $photo->owner_id)
                    ->update(['updated_at' => date('Y:m:d H:i:s')]);
            
            if(count($to_insert) || count($to_delete))
                DB::table('photos')
                ->where('photo_id', $photo->photo_id)
                ->update(['updated_at' => date('Y:m:d H:i:s')]);
            // Photo Tags 
            
        } elseif ($photo->owned_by == 'album') {
            $photo->title = $imagetitlep;
            $photo->photo_by = $imagebyp;
            $photo->description = $descriptionp;
            $photo->source = $photosourcep;
            $photo->source_url = $sourceurlp;
            $return = '
                                            <td width="20%">
                                                <img style="width:40%;" alt="album image" src="' . config('constants.awsbaseurl') . config('constants.awalbumimagedir') . $photo->photopath . '">
                                            </td>
                                           <td width="20%">' . $photo->title . '</td>
                                             <td width="30%" class="tdimagedesc">' . $photo->description . '</td>
                                            <td width="15%">' . $photo->photo_by . '</td>
                                    <input type="hidden" id="' . $photo->photo_id . '" name="deleteImagel">
                                    <td with="15%" class="center">
                                        <button class="btn btn-mini btn-danger" id="deleteImage" name="' . $photo->photo_id . '" onclick="$(this).MessageBox(' . $photo->photo_id . ')" type="button">Dump</button>
                                        <button class="btn btn-mini btn-edit" id="deleteImage" name="image' . $photo->photo_id . '" onclick="editImageDetail(' . $photo->photo_id . ',\'album\')" type="button">Edit</button>
                                        <img style="width:20%; display:block; margin-left:15px;display:none;" alt="loader" src="' . asset('images/photon/preloader/76.gif') . '"></td>
                                    ';
            DB::table('album')
                    ->where('id', $photo->owner_id)
                    ->update(['updated_at' => date('Y:m:d H:i:s')]);
        }
        $photo->save();
        return $return;
        //print_r($request->all());
    }

    /*
     * Check if The User ID passed has Rights to Edit the Article
     *
     * passes User ID, Article ID, User Rights
     * @returns boolean 1:0
     */

public function channelarticles($option) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
        //exit;
       
        //echo 'test'; exit;
        /* Right mgmt start */
       if(isset($_GET['channelf'])){
         $channelf = $_GET['channelf'];
        }else{
            $channelf = 1;
        }
        if(isset($_GET['channel'])){
         $currentChannelId = $_GET['channel'];
         
        }else{
         $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
         
        }
        $channels = $this->rightObj->getAllowedChannels($rightId);

        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
        /* Right mgmt end */

        $editor = '';
        $ChennalArr = DB::table('channels')->get();
        //For My Drafts Page
            $i = 0;
            $articles = array();
            DB::enableQueryLog();
            $condition = '';

            $q = DB::table('articles')
                    ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                    ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id');
            
                $q->select(DB::raw('articles.article_id,articles.title,articles.auto_published,articles.publish_date,articles.publish_time,group_concat(authors.name) as name,articles.channel_id,articles.locked_by'));
                
                $q->orderBy('articles.publish_date', 'desc');
                $q->orderBy('articles.publish_time', 'desc');
                
            
            $q->where('articles.channel_id', $channelf)
                    ->where('status', 'p');
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
                    ->where('status', 'p')
                    ->get();
        if(isset($_GET['channelf'])){
        $idchannelf = $_GET['channelf'];
         }else{
            $idchannelf = '';
            }
       
        //echo $currentChannelId; exit;
        //print_r($channels); exit;
        return view('channelarticles.' . $option, compact('articles', 'editor', 'channels', 'currentChannelId','idchannelf','ChennalArr'));
    }



    public function sortImage($id, Request $request) {

        foreach ($request->row as $k => $itm) {
            $articlePhoto = Photo::find($itm);
            $articlePhoto->sequence = $k + 1;
            $articlePhoto->updated_at = date('Y-m-d H:i:s');
            $articlePhoto->save();
        }

        DB::table('articles')
                ->where('article_id', $id)
                ->update(['updated_at' => date('Y:m:d H:i:s')]);
    }

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
    private function isLocked($article){
        $uid=Session::get('users')->id;
        //echo $uid.'--'.$article->locked_by; exit;
        if($article->locked_by!=$uid && $article->locked_by>0)
            return true;
      
        return false;
    }
    
    public function unlock($id){
        $uid=Session::get('users')->id;
        $article=Article::find($id);
        $currentChannelId = $article->channel_id;
        if($uid==$article->locked_by){ // If article is unlocked by same user(who locked) check edit permisssion only.
            $rightId=8;
        }else{// Unlock article locked by other user. 
            $rightId=14;
        }
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
        $article->locked_by=0;
        $article->update();
        if(isset($_GET['destination'])){
            $url=urldecode($_GET['destination']);
        }else{
            $url=url('article/list/published');
        }
       return redirect()->away($url);
    }

    public function show($id) {
        //dd($_SERVER);
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
        $is_locked=0;
        if($this->isLocked($article)){
            $lockedBy=User::find($article->locked_by);
             Session::flash('error', 'This Article is locked by '.$lockedBy->name.'. At : '.date('h:i a,d-M-Y',strtotime($article->locked_at)));
             $is_locked=1;
            //echo 'Locked by someone else'; exit;
        }else{
            //Lock Article for Editor
            $addArticle = Article::find($id);
            $addArticle->locked_by = $uid;
            $addArticle->locked_at = date('Y-m-d H:i:s');
            $addArticle->save();
        }
        
        /* Right mgmt start */
        $rightId = 8;
        $currentChannelId = $article->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        //Get Author Ids and Names - with label 1, 2, 3
        if($article->author_type!=6){            
            $arrAuth = DB::table('article_author')
                            ->join('authors', 'authors.author_id', '=', 'article_author.author_id')
                            ->select('article_author.author_id as id', 'authors.name')
                            ->where('article_id', '=', $id)->get();
        }else{            
            $arrAuth = DB::table('article_author')
                            ->join('event_speaker', 'event_speaker.id', '=', 'article_author.author_id')
                            ->select('article_author.author_id as id', 'event_speaker.name')
                            ->where('article_id', '=', $id)->get();
            //print_r($arrAuth); exit;
        }
        $authors=json_encode($arrAuth);

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

        //$arrPhotos = Photo::where('owned_by', '=', 'article')
        //                ->where('owner_id', '=', $id)->count(); 
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
        $category = DB::table('category')->where('channel_id', $currentChannelId)->where('valid', '1')->orderBy('name')->get();

        $magazine = DB::table('magazine')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $event = DB::table('event')->where('channel_id', $currentChannelId)->where('valid', '1')->orderBy("start_date","desc")->get();
        $campaign = DB::table('campaign')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $columns = DB::table('columns')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
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
                ->orderBy('sequence')
                ->get();
        //echo 'test'; exit;
        return view('articles.edit', compact('article', 'rights', 'channels', 'p1', 'postAs', 'country', 'states', 'newstype', 'category', 'magazine', 'event', 'campaign', 'columns', 'tags', 'photos', 'acateg', 'arrAuth','authors', 'arrTags', 'arrVideo', 'userTup', 'arrTopics','is_locked'));
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
        //echo  Session::getId(); 
        //echo date('Y-m-d'); 
        //echo date('H:i:s'); 
        //exit;
        // exit;
        $uid = Session::get('users')->id;

        /* Right mgmt start */
        $rightId = 2;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
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
        $category = DB::table('category')->where('channel_id', $currentChannelId)->where('valid', '1')->orderBy('name')->get();

        //fwrite($asd, "\n Channels SELECTED FOR USER ".$channels[1]->channel_id."\n");

        $magazine = DB::table('magazine')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $event = DB::table('event')->where('channel_id', $currentChannelId)->where('valid', '1')->orderBy('start_date','desc')->get();
        $campaign = DB::table('campaign')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $columns = DB::table('columns')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        //$tags = DB::table('tags')->where('valid', '1')->get();

        return view('articles.create', compact('channels', 'p1', 'postAs', 'country', 'states', 'newstype', 'category', 'magazine', 'event', 'campaign', 'columns', 'tags', 'currentChannelId'));
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
        $rightId = 8;
        $currentChannelId = $request->channel_sel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        // Checking publish permission
        if ($request->status == 'D' || $request->status == 'SD') {
            if (!$this->rightObj->checkRights($currentChannelId, 13))
                return redirect('/dashboard');
        }
        if ($request->status == 'P') {
            if (!$this->rightObj->checkRights($currentChannelId, 12))
                return redirect('/dashboard');
        }

        /* Right mgmt end */



        $uid = $request->user()->id;
        //fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");
        $article = Article::find($request->id);
        $oldAuthorType=$article->author_type;
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
        $article->auto_published = 0;
        if($request->authortype=="6")
            $article->event_id = $request->event_id_author;
        else
            $article->event_id = $request->event;
        $article->rating_point = $request->rating_point;
        if ($request->social_image)
            $article->social_image = $request->social_image;
        if ($request->hide_image)
            $article->hide_image = $request->hide_image;
        if ($request->video_Id != '' && $request->video_Id != '0') {
            $article->video_type = 'uploadedvideo';
        } elseif ($request->videoCode != '') {
            $article->video_type = 'embededvideocode';
        }
        if ($request->publish_date_time_change != ''){
                $article->publish_date = date("Y-m-d", strtotime($request->publish_date_time_change));
                $article->publish_time = date('H:i:s');
            }
        $article->video_Id = $request->video_Id;
        $article->canonical_options = $request->canonical_options;
        $article->canonical_url = $request->canonical_url;
        $article->social_title = $request->social_title;
        $article->social_summary = $request->social_summary;
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
        $article->exclusive_non_featured = $request->exclusive_non_featured ? 1 : 0;
        $article->featured_in_print = $request->featured_in_print ? 1 : 0;
        $article->slug = 'slug';
        //echo $request->status;exit;
        if ($request->status != 'SV')
            $article->status = $request->status;

        //Only for Schedule Article Action
        if ($request->status == 'SD') {
            //echo $request->datepicked; 
            $article->publish_date = trim($request->datepicked) ? $request->datepicked : date('Y-m-d');
            $article->publish_time = trim($request->timepicked) ? $request->timepicked : date('H:i:s');
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
            foreach ($eAuthors as $each) {
                $arrCollect[$each->article_author_rank][0] = $each->author_id;
                $arrCollect[$each->article_author_rank][1] = $each->article_author_id;
            }
        }
        //Save in Author Associative Table - for any other than Online Bureau
        if ($request->authortype != '1') {
            $author_count = 0;
            //For BW Reporters - Multiple
            
            
            if ($request->author) {
                $autorids = explode(',', $request->author);
                $autorids = array_unique($autorids);
                if($oldAuthorType==$request->authortype)
                    $deleteOldAuthor=DB::table('article_author')->where('article_id', '=', $id)->whereNotIn('author_id',$autorids)->delete();
                else
                    $deleteOldAuthor=DB::table('article_author')->where('article_id', '=', $id)->delete();
                
                foreach ($autorids as $key => $value) {
                    $author_count++;
                    if (DB::table('article_author')->where('article_id', '=', $id)->where('author_id', $value)->count() == 0) {
                        $article_author = new ArticleAuthor();
                        $article_author->article_id = $id;
                        $article_author->channel_id = $request->channel_sel;
                        $article_author->article_author_rank = $author_count;
                        $article_author->author_id = $value;
                        $article_author->valid = '1';
                        $article_author->save();
                    }
                }
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
        if ($request->get('rimage')) {
            foreach ($request->get('rimage') as $key => $value) {
                $oldPhoto = Photo::find($key);
                $articleImage = new Photo();
                $articleImage->photopath = $oldPhoto->photopath;
                $articleImage->photo_by = $value;
                $articleImage->title = isset($request->rtitle[$key]) ? $request->rtitle[$key] : '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
                // echo $key; exit;
            }
        }
        $images = explode(',', $request->uploadedImages);
        $images = array_filter($images);

        if (count($images) == 0) {
            $photosCount = DB::table('photos')->where('valid', '1')
                    ->where('owned_by', 'article')
                    ->where('owner_id', $article->article_id)
                    ->count();
            if (($photosCount == 0) && (trim($request->video_Id) != '') && (trim($request->video_Id) != '0')) {
                $selectedvideo = MasterVideo::find($request->video_Id);
                if ($selectedvideo->video_by == 'inhouse') {
                    $source = config('constants.awsbaseurl') . config('constants.awvideothumb') . urlencode($selectedvideo->video_thumb_name);
                    $dest = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $selectedvideo->video_thumb_name;
                    if (copy($source, $dest)) {
                        $images[] = $selectedvideo->video_thumb_name;
                        //$request->photographby[$selectedvideo->video_thumb_name]='';
                    }
                }
            }
        }


        //print_r($images); exit;
        //exit;
        $fileTran=new FileTransfer();
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
        foreach ($images as $image){ 
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $source = '';
            $dest =config('constants.awarticleimageextralargedir');
            $fileTran->tranferFile($image, $source, $dest,false);
            $destination =config('constants.awarticleimagethumbtdir');
            $fileTran->resizeAndTransferFile($image,'100X69',$source,$destination);
            $destination =config('constants.awarticleimagemediumdir');
            $fileTran->resizeAndTransferFile($image,'159X106',$source,$destination);
            $destination =config('constants.awarticleimagelargedir');
            $fileTran->resizeAndTransferFile($image,'367X232',$source,$destination);
            
            unlink($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image);
            if (is_file($source_thumb))
                    unlink($source_thumb);

                //unlink($dest);
                $articleImage = new Photo();
                $articleImage->photopath = $image;
                $articleImage->imagefullPath = '';
                $articleImage->photo_by = isset($request->photographby[$image]) ? $request->photographby[$image] : '';
                $articleImage->title = isset($request->imagetitle[$image]) ? $request->imagetitle[$image] : '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            
            //}
        }
        
        if($request->whatsapp_bd && $article->status == 'P'){
            $channel=Channel::find($request->channel_sel);
            $publish_date=date('d-m-Y',strtotime($article->publish_date));
            $article_id=$article->article_id;
            $url= $channel->channelurl.'/article/'.preg_replace('/([^a-zA-Z0-9]){1,}/', '-',$article->title).'/'.$publish_date.'-'.$article_id.'/?utm_source=whatsapp&utm_medium=newsletter';
            $data['message']=trim($article->title).'  '.$url;
            $photo=Photo::where('owner_id','=',$article->article_id)->where('owned_by','=','article')->orderBy('sequence','asc')->first();
            if($photo){
                $data['attachment']= config('constants.awsbaseurl').config('constants.awarticleimagelargedir').$photo->photopath;
            }
            
            $server_output=GeneralFunctions::sendWhatsappBroadcast($data);
            $dataArray=json_decode($server_output);
            if(trim($dataArray->code)=='200'){
                $article->whatsapp_bd='1';
                $article->update();
            }
        }

        if ($article->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published?channel=' . $currentChannelId);
        } elseif ($article->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new?channel=' . $currentChannelId);
        } elseif ($article->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted?channel=' . $currentChannelId);
        } elseif ($article->status == 'SD') {
            Session::flash('message', 'Your Article has been Scheduled successfully.');
            return redirect('/article/list/scheduled?channel=' . $currentChannelId);
        } else {
            Session::flash('message', 'Your Article has been saved in your draftsuccessfully.');
            return redirect('/article/list/drafts?channel=' . $currentChannelId);
        }

        //return Redirect::to('/dashboard');
        //return view('/dashboard');
    }

    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        /* Right mgmt start */
        $rightId = 2;
        $currentChannelId = $request->channel_sel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        //print_r($request->all()); exit;

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
         if($request->authortype=="6")
            $article->event_id = $request->event_id_author;
        else
            $article->event_id = $request->event;
        $article->canonical_options = $request->canonical_options;
        $article->canonical_url = $request->canonical_url;
        $article->social_title = $request->social_title;
        $article->social_summary = $request->social_summary;
        $article->video_Id = $request->video_Id;
        $article->rating_point = $request->rating_point;
        //social_image
        if ($request->social_image)
            $article->social_image = $request->social_image;
        if ($request->hide_image)
            $article->hide_image = $request->hide_image;
        //print_r($request->all());
        if ($request->video_Id != '') {
            $article->video_type = 'uploadedvideo';
        } elseif ($request->videoCode != '') {
            $article->video_type = 'embededvideocode';
        }
        //exit;
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
            $article->publish_date = trim($request->datepicked) ? $request->datepicked : date('Y-m-d');
            $article->publish_time = trim($request->timepicked) ? $request->timepicked : date('H:i:s');
        } elseif ($request->status == 'P') {
            $article->publish_date = date('Y-m-d');
            $article->publish_time = date('H:i:s');
        }

        $article->important = $request->important ? 1 : 0;
        $article->web_exclusive = $request->web_exclusive ? 1 : 0;
        $article->exclusive_non_featured = $request->exclusive_non_featured ? 1 : 0;
        $article->featured_in_print = $request->featured_in_print ? 1 : 0;

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
            
            if ($request->author) {
                $autorids = explode(',', $request->author);
                $autorids = array_unique($autorids);
                foreach ($autorids as $key => $value) {
                    $author_count++;
                    $article_author = new ArticleAuthor();
                    $article_author->article_id = $id;
                    $article_author->channel_id = $request->channel_sel;
                    $article_author->article_author_rank = $author_count;
                    $article_author->author_id = $value;
                    $article_author->valid = '1';
                    $article_author->save();
                }
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
        if ($request->get('rimage')) {
            foreach ($request->get('rimage') as $key => $value) {
                $oldPhoto = Photo::find($key);
                $articleImage = new Photo();
                $articleImage->photopath = $oldPhoto->photopath;
                $articleImage->photo_by = $value;
                $articleImage->title = isset($request->rtitle[$key]) ? $request->rtitle[$key] : '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
                // echo $key; exit;
            }
        }
        
        $images = explode(',', $request->uploadedImages);
        $images = array_filter($images);
        //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");

        if (count($images) == 0) {
            if ((trim($request->video_Id) != '') && (trim($request->video_Id) != '0')) {
                $selectedvideo = MasterVideo::find($request->video_Id);
                if ($selectedvideo->video_by == 'inhouse') {
                    $source = config('constants.awsbaseurl') . config('constants.awvideothumb') . urlencode($selectedvideo->video_thumb_name);
                    $dest = $_SERVER['DOCUMENT_ROOT'] . '/files/' . $selectedvideo->video_thumb_name;
                    if (copy($source, $dest)) {
                        $images[] = $selectedvideo->video_thumb_name;
                    }
                }
            }
        }

        $fileTran=new FileTransfer();
        
        foreach ($images as $image) {
            $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
            $source = '';
            $dest =config('constants.awarticleimageextralargedir');
            $fileTran->tranferFile($image, $source, $dest,false);
            $destination =config('constants.awarticleimagethumbtdir');
            $fileTran->resizeAndTransferFile($image,'100X69',$source,$destination);
            $destination =config('constants.awarticleimagemediumdir');
            $fileTran->resizeAndTransferFile($image,'159X106',$source,$destination);
            $destination =config('constants.awarticleimagelargedir');
            $fileTran->resizeAndTransferFile($image,'367X232',$source,$destination);
            
                if(is_file($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image))
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/files/'.$image);
                if (is_file($source_thumb))
                    unlink($source_thumb);
                $articleImage = new Photo();
                $articleImage->photopath = $image;
                $articleImage->imagefullPath = '';
                $articleImage->photo_by = isset($request->photographby[$image]) ? $request->photographby[$image] : '';
                $articleImage->title = isset($request->imagetitle[$image]) ? $request->imagetitle[$image] : '';
                $articleImage->channel_id = $request->channel_sel;
                $articleImage->owned_by = 'article';
                $articleImage->owner_id = $id;
                $articleImage->active = '1';
                $articleImage->created_at = date('Y-m-d H:i:s');
                $articleImage->updated_at = date('Y-m-d H:i:s');
                $articleImage->save();
            //}
            //   }
        }

        //}
        //Add article_id to Relational Tables;
        //     exit;        
        //fwrite($asd, "Step 3.1 In Article POST Function END ING \n");        
        //If has been Saved by Editor
        if($request->whatsapp_bd && $article->status == 'P'){
            $channel=Channel::find($request->channel_sel);
            $publish_date=date('d-m-Y',strtotime($article->publish_date));
            $article_id=$article->article_id;
            $url= $channel->channelurl.'/article/'.preg_replace('/([^a-zA-Z0-9]){1,}/', '-',$article->title).'/'.$publish_date.'-'.$article_id.'/?utm_source=whatsapp&utm_medium=newsletter';
            $data['message']=trim($article->title).'  '.$url;
            $photo=Photo::where('owner_id','=',$article->article_id)->where('owned_by','=','article')->orderBy('sequence','asc')->first();
            if($photo){
                $data['attachment']= config('constants.awsbaseurl').config('constants.awarticleimagelargedir').$photo->photopath;
            }
            $server_output=GeneralFunctions::sendWhatsappBroadcast($data);
            $dataArray=json_decode($server_output);
            if(trim($dataArray->code)=='200'){
                $article->whatsapp_bd='1';
                $article->update();
            }
        }
        if ($request->status == 'P') {
            Session::flash('message', 'Your Article has been Published successfully. It will appear on website shortly.');
            return redirect('/article/list/published?channel=' . $currentChannelId);
        }
        if ($request->status == 'N') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/article/list/new?channel=' . $currentChannelId);
        }
        if ($request->status == 'D') {
            Session::flash('message', 'Your Article has been Deleted successfully.');
            return redirect('/article/list/deleted?channel=' . $currentChannelId);
        }
        if ($request->status == 'S') {
            Session::flash('message', 'Your Article has been saved successfully in - My Drafts.');
            return redirect('/article/list/drafts?channel=' . $currentChannelId);
        }
        if ($article->status == 'SD') {
            Session::flash('message', 'Your Article has been Scheduled successfully.');
            return redirect('/article/list/scheduled?channel=' . $currentChannelId);
        }

        return redirect('/article/list/new?channel=' . $currentChannelId);

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
        $rightId = 12;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
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

public function articlechannelinsert(Request $request) {

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

        $uid = Session::get('users')->id;
        
       if (isset($_POST['checkItem'])) {
        $ArArr = $_POST['checkItem'];
         $channel_selfm = $_POST['channel_selfm'];
         $channel_sel = $_POST['channel_sel'];
         //print_r($ArArr);
         $ChennalArr = DB::table('channels')->where('channel_id', '=', $channel_sel)->first();
         $ChennalArr->channelurl;
         //print_r($ChennalArr);
         //exit();
         //DB::enableQueryLog();
        $ArticleArr=Article::whereIn('article_id', $ArArr)->where('channel_id', '=', $channel_sel)->get();
        //dd(DB::getQueryLog());
            //print_r($ArticleArr);
            //exit();
                foreach($ArticleArr as $articleRow) {
                    $article = new Article();
                    // Add Arr Data to Article Table //
                    $publish_date=date('d-m-Y',strtotime($articleRow->publish_date));
                    $title=  str_replace(' ', '-', $articleRow->title);
                    $article->channel_id = $channel_selfm;
                    $article->user_id = $uid;
                    $article->author_type = $articleRow->author_type;
                    $article->is_columnist = $articleRow->is_columnist;                  
                    $article->title = $articleRow->title;
                    $article->summary = $articleRow->summary;
                    $article->description = $articleRow->description;
                    $article->country = $articleRow->country;
                    $article->state = $articleRow->state;
                    $article->news_type = $articleRow->news_type;
                    $article->magazine_id = $articleRow->magazine_id;                    
                    $article->event_id = $articleRow->event_id;
                    $article->canonical_options = $articleRow->canonical_options;
                    $article->view_count = $articleRow->view_count;
                    $article-> exclusive_non_featured = $articleRow->exclusive_non_featured;
                    $article-> featured_in_print = $articleRow->featured_in_print;
                    $article-> send_mail_status = $articleRow->send_mail_status;
                    $article-> bitly_url = $articleRow->bitly_url;
                    $article-> auto_published = $articleRow->auto_published;
                    $article->copyarticle_id = $articleRow->article_id;
                    if($articleRow->canonical_url!=''){
                    $article->canonical_url = $articleRow->canonical_url;
                    }else{
                        $article->canonical_url = $ChennalArr->channelurl.'/article/'. preg_replace('/([^a-zA-Z0-9_.])+/', '-',$title). '/'.$publish_date.'-'.$articleRow->article_id;
                        }
                    $article->canonical_options = 1;
                    $article->social_title = $articleRow->social_title;
                    $article->social_summary = $articleRow->social_summary;
                    $article->video_Id = $articleRow->video_Id;
                    $article->rating_point = $articleRow->rating_point;
                    $article->social_image = $articleRow->social_image;
                    $article->hide_image = $articleRow->hide_image;
                    $article->video_type = $articleRow->video_type;
                    $article->campaign_id = $articleRow->campaign_id;
                    $article->for_homepage = $articleRow->for_homepage;
                    $article->publish_date = $articleRow->publish_date;
                    $article->publish_time = $articleRow->publish_time;
                    $article->important = $articleRow->important;
                    $article->web_exclusive = $articleRow->web_exclusive;
                    $article->slug = $articleRow->slug;
                    $article->status = $articleRow->status;
                    $article->save();
                    $oldid= $articleRow->article_id;
                    //Get Article_id
                     $id = $article->article_id;
                   
        //DB::enableQueryLog();
        $channelAth=ArticleAuthor::where('article_id', $oldid)->get();
        //dd(DB::getQueryLog());
         foreach($channelAth as $authRow) {
        // Assignig static author if author type is online bureau
            $article_author = new ArticleAuthor();
            $article_author->article_id = $id;
            $article_author->channel_id = $channel_selfm;
            $article_author->article_author_rank = $authRow['article_author_rank'];
            $article_author->author_id = $authRow['author_id']; // It's fixed in for all onlien bureau
            $article_author->valid = $authRow['valid'];
            //print_r($ArticleArr);
             // echo   $authRow['valid'];
            $article_author->save();
        }
        //Article Topics - Save
        $channelAto=ArticleTopic::where('article_id', $oldid)->get();
        if (count($channelAto) > 0){
         foreach($channelAto as $topicRow) {
                $article_topics = new ArticleTopic();
                $article_topics->article_id = $id;
                $article_topics->topic_id = $topicRow['topic_id'];
                 echo $topicRow['topic_id'];
                
                $article_topics->save();
            }
        }
        
        //Article Tags - Save
        $channelAtg=ArticleTag::where('article_id', $oldid)->get();
        //print_r($channelAtg);
        
        if (count($channelAtg) > 0){
         foreach($channelAtg as $tagRow) { 
                $article_tags = new ArticleTag();
                $article_tags->article_id = $id;
                $article_tags->tags_id = $tagRow['tags_id'];
                $article_tags->save();
            }
        }


            $CategoryArr = DB::table('category')->where('channel_id', '=', $channel_selfm)->first();
                   
            $article_category = new ArticleCategory();
            $article_category->article_id = $id;
            $article_category->category_id = $CategoryArr->category_id;
            $article_category->level = 1;
            $article_category->save();
         

        //- Update article_id to respective table -//
        //Video table (article_id)- Save
        $channelVid=Video::where('owner_id', $oldid)->get();
        if (count($channelVid) > 0){
         foreach($channelVid as $VidRow) {  
            $objVideo = new Video();
            $objVideo->title = $VidRow['title'];
            $objVideo->code = $VidRow['code'];
            $objVideo->source = $VidRow['source'];
            $objVideo->url = $VidRow['url'];
            $objVideo->channel_id = $channel_selfm;
            $objVideo->owned_by = $VidRow['owned_by'];
            $objVideo->owner_id = $VidRow['owner_id'];
            $objVideo->added_by = $VidRow['added_by'];
            $objVideo->added_on = $VidRow['added_on'];
            $objVideo->save();
        }

        }
         //DB::enableQueryLog();
         $channelpho=Photo::where('owner_id', $oldid)->get();
         //print_r($channelpho);
          //dd(DB::getQueryLog());
         if (count($channelpho) > 0){
         foreach($channelpho as $phoRow) {
            
                $articleImage = new Photo();
                $articleImage->photopath = $phoRow['photopath'];
                $articleImage->photo_by = $phoRow['photo_by'];
                $articleImage->title = $phoRow['title'];
                $articleImage->channel_id = $channel_selfm;
                $articleImage->description = $phoRow['description'];
                $articleImage->imagefullPath = $phoRow['imagefullPath'];
                $articleImage->sequence = $phoRow['sequence'];
                $articleImage->source = $phoRow['source'];
                $articleImage->source_url = $phoRow['source_url'];
                $articleImage->channel_id = $phoRow['channel_id'];
                $articleImage->owned_by = $phoRow['owned_by'];
                $articleImage->owner_id = $id;
                $articleImage->active = $phoRow['active'];
                $articleImage->created_at = $phoRow['created_at'];
                $articleImage->updated_at = $phoRow['updated_at'];
                $articleImage->save();
                //echo 'sumitsdsdsfsd---';
            }
           }
        //return redirect('/article/list/new?channel=' . $channel_selfm);

        //return redirect('/dashboard');
        //return Redirect::to('/dashboard');
        //return view('/dashboard');
            }
            
        }
        
        return 'success';
    }

    public function publishScheduledArticle() {
        if (!Session::has('users')) {
            return 'Please login first.';
        }

        /* Right mgmt start */
        $rightId = 12;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return 'You are not authorized to access';
        /* Right mgmt end */

        $articles = DB::table('articles')
                ->where('status', 'SD')
                ->where('publish_date', '<=', date('Y-m-d'))
                ->where('publish_time', '<=', date('H:i:s'))
                ->get();
        //echo count($articles);exit;
        foreach ($articles as $article) {
            $updatearticle = Article::find($article->article_id);
            $updatearticle->status = 'P';
            $updatearticle->update();
        }
        return 'success';
    }

//   function relatedImage(Request $request){ //13803,13546
//       //echo $query="select *,count(*) as cs,MATCH(tag) AGAINST ('".$request->search_key."') as score from tags join article_tags on tags.tags_id=article_tags.tags_id where MATCH(tag) AGAINST ('".$request->search_key."') group by tags.tags_id order by article_tags.updated_at desc,score desc limit 5"; exit;
//       $query="select *,MATCH(tag) AGAINST ('".$request->search_key."') as score from tags where MATCH(tag) AGAINST ('".$request->search_key."') order by updated_at desc,score desc limit 20";
//       $tags=DB::select($query);
//       $related_images=array();
//       $imgids=array();
//       $cond='';
//      // $rtags=array();
//       foreach($tags as $tag){
//           if(count($imgids)>0){
//               $cond=' and photo_id not in ('.implode(',',$imgids).')';
//           }
//          $imagequery="select photo_id,photopath,photo_by from photos where valid='1' and photopath!='' and owned_by='article' $cond and owner_id in(SELECT articles.article_id FROM `articles` inner join article_tags on articles.article_id=article_tags.article_id WHERE  article_tags.tags_id=".$tag->tags_id.") order by updated_at desc";
//          $images=DB::select($imagequery);
//            foreach($images as $image){
//                $imgids[]=$image->photo_id;
//             $related_images[]=array('image_url'=>config('constants.awsbaseurl').config('constants.awarticleimagethumbtdir').$image->photopath,'image_id'=>$image->photo_id,'tag_name'=>$tag->tag,'tag_id'=>$tag->tags_id,'photo_by'=>$image->photo_by,'image_name'=>$image->photopath);
//          }
//          
//        }
//       
//       return json_encode($related_images);
//   }

    function relatedImage(Request $request) {//13803,13546
        //$query="select *,MATCH(tag) AGAINST ('".$request->search_key."') as score from tags where MATCH(tag) AGAINST ('".$request->search_key."') order by updated_at desc,score desc limit 5";
        $total = 25;
        $query = "select *,count(*) as cs from tags join (select * from article_tags order by updated_at desc ) as article_tags on tags.tags_id=article_tags.tags_id where (tag like '%" . $request->search_key . " %' or tag like '% " . $request->search_key . "%'  or tag like '" . $request->search_key . "' ) group by tags.tags_id order by article_tags.updated_at desc,cs desc limit 5";
        $tags = DB::select($query);
        $related_images = array();
        $imgids = array();
        $cond = '';
        // print_r($tags);
        usort($tags, array($this, 'compareByCount'));
        //print_r($tags);exit;
        //  print_r($tags);
        if (count($tags) > 0) {
            $minlimit = ceil($total / count($tags));
            $maxlimit = ceil($total / count($tags));
            //echo $limit;exit;
            // $rtags=array();
            foreach ($tags as $tag) {
                if (count($imgids) > 0) {
                    $cond = ' and photo_id not in (' . implode(',', $imgids) . ')';
                }
                //echo $maxlimit;
                $imagequery = "select photo_id,photopath,photo_by,title from photos where valid='1' and photopath!='' and owned_by='article' $cond and owner_id in(SELECT articles.article_id FROM `articles` inner join article_tags on articles.article_id=article_tags.article_id WHERE  article_tags.tags_id=" . $tag->tags_id . " order by article_tags.updated_at desc) group by photopath order by updated_at desc limit " . $maxlimit;
                $images = DB::select($imagequery);
                if (count($images) < $maxlimit) {
                    $maxlimit = $maxlimit + ($minlimit - count($images));
                } else {
                    $maxlimit = $minlimit;
                }
                //echo count($images).' ######'.$maxlimit.'##### <br>';
                foreach ($images as $image) {
                    $imgids[] = $image->photo_id;
                    $related_images[] = array('image_url' => config('constants.awsbaseurl') . config('constants.awarticleimagethumbtdir') . $image->photopath, 'image_id' => $image->photo_id, 'tag_name' => $tag->tag, 'tag_id' => $tag->tags_id, 'photo_by' => $image->photo_by, 'image_name' => $image->photopath, 'title' => $image->title);
                }
            }
        } else {
            json_encode(array('error' => 'No result found'));
        }
        return json_encode($related_images);
    }

    public static function compareByCount($a, $b) {
        return strcmp($a->cs, $b->cs);
    }
public function trending() {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
        
        //echo 'test'; exit;
        /* Right mgmt start */
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $editor = '';
        $trending=Trending::first();
        //dd($trending);
        return view('trandingnow.' . 'trending', compact('trending'));
    }

public function trendinginsert(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
        
        //echo 'test'; exit;
        /* Right mgmt start */
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        echo $request->id;
       
        
        //DB::enableQueryLog();
        if($request->id !=''){
            $trending = Trending::find($request->id);
            //print_r($trending);
           
        }else{
           $trending = new Trending();
        }
         
        //dd(DB::getQueryLog());

        $trending->channel_id = '1';
        $trending->user_id = $uid;
        $trending->author_type = '1';
        $trending->t1topic = $request->t1topic;                  
        $trending->t1url = $request->t1url;
        $trending->t1article1 = $request->t1article1;
        $trending->t1a1url = $request->t1a1url;
        $trending->t1article2 = $request->t1article2;
        $trending->optionsRadios = $request->optionsRadios;
        $trending->t1a1url2 = $request->t1a1url2;
        $trending->t2topic = $request->t2topic;
        $trending->t2url = $request->t2url;                    
        $trending->t2article1 = $request->t2article1;
        $trending->t2a1url = $request->t2a1url;
        $trending->t2article2 = $request->t2article2;
        $trending->t2a1url2 = $request->t2a1url2;
        $trending->t3topic = $request->t3topic;
        $trending->t3url = $request->t3url;
        $trending->t3article1 = $request->t3article1;
        $trending->t3a1url = $request->t3a1url;
        $trending->t3article2 = $request->t3article2;
        $trending->t3a1url2 = $request->t3a1url2;
       if(!empty($request->id)){
            $trending->update();
        }else{
            $trending->save();
        }
        return 'success';
    }

public function preview(){
    $title=session('title');
    $summary=session('summary');
    $description=session('description');
    return view('articles.articlepreview', compact('title', 'summary', 'description'));
}
    
public function previewSet(Request $request){
    session(['title' => $request->title]);
    session(['summary' => $request->summary]);
    session(['description' => $request->description]);
   return response('done');
}

//
//public function articlechannelinsertGet(Request $request) {
//
////        if (!Session::has('users')) {
////            return 'Please login first.';
////        }
//
//        /* Right mgmt start */
////        $rightId = 13;
////        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
////        $channels = $this->rightObj->getAllowedChannels($rightId);
////        if (!$this->rightObj->checkRights($currentChannelId, $rightId)) {
////            return 'You are not authorized to access';
////        }
//        /* Right mgmt end */
//
//        $uid = Session::get('users')->id; 
//        //
//        
//       //if (isset($_POST['checkItem'])) {
////        $ArArr = $_POST['checkItem'];
//         $channel_selfm = 12;
//         $channel_sel = 1;
//         //print_r($ArArr);
//         $ChennalArr = DB::table('channels')->where('channel_id', '=', '1')->first();
//         $ChennalArr->channelurl;
//         //print_r($ChennalArr);
//         //exit();
//         //DB::enableQueryLog();
//       // $ArticleArr=Article::whereIn('article_id', $ArArr)->where('channel_id', '=', $channel_sel)->get();
//        
//          //echo 'test'; exit;
//        //select ar.* from article_category ac inner join articles ar on ac.article_id=ar.article_id  where category_id=47334 and status='P' and ar.article_id not in (157168,156960)
//      //DB::enableQueryLog();
//           $ArticleArr=DB::table('article_category as ac')
//                    ->join('articles as ar', 'ac.article_id', '=', 'ar.article_id')
//                    ->select('ar.*')
//                    ->where('ar.channel_id', '=', $channel_sel)
//                    ->where('ar.status', '=', 'P')
//                    ->where('ac.category_id', '=', '47334')
//                    ->whereNotIn('ar.article_id', [157168,156960])
//                    ->get();
//      //echo 'test'; exit;
//         
//        // echo 'test'; exit;
//        //$ArticleArr='';
//        //dd(DB::getQueryLog());
//            //print_r($ArticleArr);
//            //exit();
//           $i=0;
//                foreach($ArticleArr as $articleRow) {
//                    //dd($articleRow); exit;
//                    $article = new Article();
//                    // Add Arr Data to Article Table //
//                    $publish_date=date('d-m-Y',strtotime($articleRow->publish_date));
//                    $title=  str_replace(' ', '-', $articleRow->title);
//                    $article->channel_id = $channel_selfm;
//                    $article->user_id = $uid;
//                    $article->author_type = $articleRow->author_type;
//                    $article->is_columnist = $articleRow->is_columnist;                  
//                    $article->title = $articleRow->title;
//                    $article->summary = $articleRow->summary;
//                    $article->description = $articleRow->description;
//                    $article->country = $articleRow->country;
//                    $article->state = $articleRow->state;
//                    $article->news_type = $articleRow->news_type;
//                    $article->magazine_id = $articleRow->magazine_id;                    
//                    $article->event_id = $articleRow->event_id;
//                    $article->canonical_options = $articleRow->canonical_options;
//                    $article->view_count = $articleRow->view_count;
//                    $article-> exclusive_non_featured = $articleRow->exclusive_non_featured;
//                    $article-> featured_in_print = $articleRow->featured_in_print;
//                    $article-> send_mail_status = $articleRow->send_mail_status;
//                    $article-> bitly_url = $articleRow->bitly_url;
//                    $article-> auto_published = $articleRow->auto_published;
//                    $article->copyarticle_id = $articleRow->article_id;
//                    if($articleRow->canonical_url!=''){
//                    $article->canonical_url = $articleRow->canonical_url;
//                    }else{
//                        $article->canonical_url = $ChennalArr->channelurl.'/'. preg_replace('/([^a-zA-Z0-9_.])+/', '-',$title). '/'.$publish_date.'-'.$articleRow->article_id;
//                        }
//                    $article->canonical_options = 1;
//                    $article->social_title = $articleRow->social_title;
//                    $article->social_summary = $articleRow->social_summary;
//                    $article->video_Id = $articleRow->video_Id;
//                    $article->rating_point = $articleRow->rating_point;
//                    $article->social_image = $articleRow->social_image;
//                    $article->hide_image = $articleRow->hide_image;
//                    $article->video_type = $articleRow->video_type;
//                    $article->campaign_id = $articleRow->campaign_id;
//                    $article->for_homepage = $articleRow->for_homepage;
//                    $article->publish_date = $articleRow->publish_date;
//                    $article->publish_time = $articleRow->publish_time;
//                    $article->important = $articleRow->important;
//                    $article->web_exclusive = $articleRow->web_exclusive;
//                    $article->slug = $articleRow->slug;
//                    $article->status = $articleRow->status;
//                    $article->save();
//                    $oldid= $articleRow->article_id;
//                    //Get Article_id
//                     $id = $article->article_id;
//                   
//        //DB::enableQueryLog();
//        $channelAth=ArticleAuthor::where('article_id', $oldid)->get();
//        //dd(DB::getQueryLog());
//         foreach($channelAth as $authRow) {
//        // Assignig static author if author type is online bureau
//            $article_author = new ArticleAuthor();
//            $article_author->article_id = $id;
//            $article_author->channel_id = $channel_selfm;
//            $article_author->article_author_rank = $authRow['article_author_rank'];
//            $article_author->author_id = $authRow['author_id']; // It's fixed in for all onlien bureau
//            $article_author->valid = $authRow['valid'];
//            //print_r($ArticleArr);
//             // echo   $authRow['valid'];
//            $article_author->save();
//        }
//        //Article Topics - Save
//        $channelAto=ArticleTopic::where('article_id', $oldid)->get();
//        if (count($channelAto) > 0){
//         foreach($channelAto as $topicRow) {
//                $article_topics = new ArticleTopic();
//                $article_topics->article_id = $id;
//                $article_topics->topic_id = $topicRow['topic_id'];
//                 //echo $topicRow['topic_id'];
//                
//                $article_topics->save();
//            }
//        }
//        
//        //Article Tags - Save
//        $channelAtg=ArticleTag::where('article_id', $oldid)->get();
//        //print_r($channelAtg);
//        
//        if (count($channelAtg) > 0){
//         foreach($channelAtg as $tagRow) { 
//                $article_tags = new ArticleTag();
//                $article_tags->article_id = $id;
//                $article_tags->tags_id = $tagRow['tags_id'];
//                $article_tags->save();
//            }
//        }
//
//
//            $CategoryArr = DB::table('category')->where('channel_id', '=', $channel_selfm)->first();
//                   
//            $article_category = new ArticleCategory();
//            $article_category->article_id = $id;
//            $article_category->category_id = $CategoryArr->category_id;
//            $article_category->level = 1;
//            $article_category->save();
//         
//
//        //- Update article_id to respective table -//
//        //Video table (article_id)- Save
//        $channelVid=Video::where('owner_id', $oldid)->get();
//        if (count($channelVid) > 0){
//         foreach($channelVid as $VidRow) {  
//            $objVideo = new Video();
//            $objVideo->title = $VidRow['title'];
//            $objVideo->code = $VidRow['code'];
//            $objVideo->source = $VidRow['source'];
//            $objVideo->url = $VidRow['url'];
//            $objVideo->channel_id = $channel_selfm;
//            $objVideo->owned_by = $VidRow['owned_by'];
//            $objVideo->owner_id = $VidRow['owner_id'];
//            $objVideo->added_by = $VidRow['added_by'];
//            $objVideo->added_on = $VidRow['added_on'];
//            $objVideo->save();
//        }
//
//        }
//         //DB::enableQueryLog();
//         $channelpho=Photo::where('owner_id', $oldid)->get();
//         //print_r($channelpho);
//          //dd(DB::getQueryLog());
//         if (count($channelpho) > 0){
//         foreach($channelpho as $phoRow) {
//            
//                $articleImage = new Photo();
//                $articleImage->photopath = $phoRow['photopath'];
//                $articleImage->photo_by = $phoRow['photo_by'];
//                $articleImage->title = $phoRow['title'];
//                $articleImage->channel_id = $channel_selfm;
//                $articleImage->description = $phoRow['description'];
//                $articleImage->imagefullPath = $phoRow['imagefullPath'];
//                $articleImage->sequence = $phoRow['sequence'];
//                $articleImage->source = $phoRow['source'];
//                $articleImage->source_url = $phoRow['source_url'];
//                $articleImage->channel_id = $phoRow['channel_id'];
//                $articleImage->owned_by = $phoRow['owned_by'];
//                $articleImage->owner_id = $id;
//                $articleImage->active = $phoRow['active'];
//                $articleImage->created_at = $phoRow['created_at'];
//                $articleImage->updated_at = $phoRow['updated_at'];
//                $articleImage->save();
//                //echo 'sumitsdsdsfsd---';
//            }
//           }
//        //return redirect('/article/list/new?channel=' . $channel_selfm);
//
//        //return redirect('/dashboard');
//        //return Redirect::to('/dashboard');
//        //return view('/dashboard');
//           $i++;
//            }
//            
//        //}
//        
//        echo $i.'-----done';
//    }
//    
}
