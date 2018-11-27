<?php

namespace App\Http\Controllers;
use App\Article;
use App\FeatureBox;
use Illuminate\Http\Request;
use App\Magazineissue;
use App\Magazineissuearticle;
use DB;
use Session;
use App\Right;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\PhotosController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Aws\Laravel\AwsFacade as AWS;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsServiceProvider;

class MagazineissueController extends Controller {

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

        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $magazineissue = DB::table('magazine')
                    ->select('magazine.*')
                    ->where('magazine.valid', '=', '1')
                    ->where('channel_id', '=', $currentChannelId)
                    ->where('magazine.title', 'LIKE', '%' . $queryed . '%')
                    ->paginate(10);
        } else {
            $magazineissue = DB::table('magazine')
                    ->select('magazine.*')
                    ->where('magazine.valid', '=', '1')
                    ->where('channel_id', '=', $currentChannelId)
                    ->orderBy('publish_date_m', 'desc')
                    ->paginate(10);
        }
        return view('magazineissue.index', compact('channels', 'magazineissue', 'currentChannelId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */
        $magazineissue = new Magazineissue;
        $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
        $imageurl = '';
        $fileTran = new FileTransfer();
        
        if ($request->file('photo')) { // echo 'test';exit;
            $file = $request->file('photo');
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            $fileTran->uploadFile($file,config('constants.awmagazinedir'), $filename); 
            $imageurl = $filename;
        }

        $magazineissue->title = $request->title;

        $magazineissue->channel_id = $request->channel;
        $magazineissue->imagepath = $imageurl;
        $magazineissue->publish_date_m = date('Y-m-d', strtotime($request->publish_date_m));
        $magazineissue->flipbook_url = $request->flipbook_url;
        $magazineissue->buy_digital = $request->buy_digital;
        $magazineissue->valid = '1';
        $magazineissue->save();
        Session::flash('message', 'Your data has been successfully modify.');
        return redirect('/magazineissue?channel=' . $request->channel);
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

       $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if (isset($_GET['channel'])) {
            $channel = $_GET['channel'];
        }
         
        $posts = DB::table('magazine')
                ->select('magazine.*')
                ->where('magazine_id', '=', $id)
                ->first();
         
        if (!(count($posts) > 0)) {
            Session::flash('error', 'There is no such article.');
            return redirect()->intended('/dashboard');
        }


        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
         $ArticleArr=Article::where('magazine_id', $id)->where('status','P')->get();
         //DB::enableQueryLog();
          $q = DB::table('articles')
                    ->Leftjoin('magazine_list', 'articles.article_id', '=', 'magazine_list.a_id');
                    
           $q->select(DB::raw('articles.article_id,articles.title,magazine_list.m_f,magazine_list.m_lw,magazine_list.m_eicn'));
               
           $SelectedArticleArr = $q->where('magazine_list.m_id', $id)->where('magazine_list.status', '1')->get();
         //$SelectedArticleArr = DB::table('articles')->Leftjoin('magazine_list', 'articles.article_id', '=', 'magazine_list.a_id')->where('magazine_list.m_id', $id);
           //dd(DB::getQueryLog());
         
        return view('magazineissue.magazineissueedite', compact('channels', 'posts', 'currentChannelId','ArticleArr','id','SelectedArticleArr'));
         
    }

     public function editmititle() {

       $uid = Session::get('users')->id;
        $rightLabel = "";

        $rightId = '';
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        if (isset($_GET['channel'])) {
            $channel = $_GET['channel'];
        }
         
        $posts = DB::table('magazine')
                ->select('magazine.*')
                ->where('magazine_id', '=', $id)
                ->get();
         
        if (!(count($posts) > 0)) {
            Session::flash('error', 'There is no such article.');
            return redirect()->intended('/dashboard');
        }


        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
         
        return view('magazineissue.magazineissueediteall', compact('channels', 'posts', 'currentChannelId','id'));
         
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
        $rightId = 77;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */


        $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
        $imageurl = '';
        $fileTran = new FileTransfer();
        if ($request->file('photo')) { // echo 'test';exit;
            $file = $request->file('photo');
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            $fileTran->uploadFile($file,config('constants.awmagazinedir'), $filename); 
            $imageurl = $filename;
            if(trim($request->photoname)){
                $fileTran->deleteFile(config('constants.awmagazinedir'),$request->photoname);
            }
        }
        //echo 'e'; exit;
        $title = $request->title;

        $channel_id = $request->channel;
        if ($request->photo) {
             $imagepath = $imageurl;
        } else {
             $imagepath = $request->photoname;
        }

        $publish_date_m = date('Y-m-d', strtotime($request->publish_date_m));
        $story1_title = $request->story1_title;
        $story1_url = $request->story1_url;
        $story2_title = $request->story2_title;
        $story2_url = $request->story2_url;
        $story3_title = $request->story3_title;
        $story3_url = $request->story3_url;
        $story4_title = $request->story4_title;
        $story4_url = $request->story4_url;
        $story5_title = $request->story5_title;
        $story5_url = $request->story5_url;
        $flipbook_url = $request->flipbook_url;
        $buy_digital = $request->buy_digital;
        $valid = '1';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $postdata = ['title' => $title,
            'channel_id' => $channel_id,
            'imagepath' => $imagepath,
            'publish_date_m' => $publish_date_m, 'story1_title' => $story1_title, 'story1_url' => $story1_url, 'story2_title' => $story2_title, 'story2_url' => $story2_url, 'story3_title' => $story3_title, 'story3_url' => $story3_url, 'story4_title' => $story4_title, 'story4_url' => $story4_url, 'story5_title' => $story5_title, 'story5_url' => $story5_url, 'flipbook_url' => $flipbook_url, 'buy_digital' => $buy_digital, 'valid' => $valid, 'created_at' => $created_at, 'updated_at' => $updated_at];
        DB::table('magazine')
                ->where('magazine_id', $request->magazine_id)
                ->update($postdata);
        Session::flash('message', 'Your data has been successfully modify.');
        return redirect('/magazineissue');
    }

 public function destroymagzinearticle() {

        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return 'You are not authorized to access.';
        /* Right mgmt end */


        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        $delArr = explode(',', $id);

        foreach ($delArr as $d) {
            $valid = '0';
           
            $deleteAl = [
                'm_f'=> 0,
                'm_lw'=> 0,
                'm_eicn'=>0,
                'status' => $valid,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('magazine_list')
                    ->where('a_id', $d)
                    ->update($deleteAl);
            
                  

        }
        return 'success';
    }
   
    
    
    
    
    
    
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return None
     */
    public function destroy() {

        /* Right mgmt start */
        $rightId = 77;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return 'You are not authorized to access.';
        /* Right mgmt end */


        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        $delArr = explode(',', $id);

        foreach ($delArr as $d) {
            $valid = '0';
            $deleteAl = [

                'valid' => $valid,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('magazine')
                    ->where('magazine_id', $d)
                    ->update($deleteAl);
            
            $deleteAl = [

                'status' => $valid,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('magazine_list')
                    ->where('m_id', $d)
                    ->update($deleteAl);        

        }
        return;
    }

public function mgainsert(Request $request) {

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
        
       if (isset($_POST['checkItemselect'])) {
        $ArArr = $_POST['checkItemselect'];     
        
        $MagissueArticle = Magazineissuearticle::whereIn('a_id', $ArArr)->get(); 
         if (count($MagissueArticle) > 0){
                foreach($MagissueArticle as $articleRow) {
                    $MgArticleup = Magazineissuearticle::find($articleRow->id);
                    $MgArticleup->m_f = '1';
                    $MgArticleup->status = '1';
                    $MgArticleup->update();
                }
            }
            else{
                foreach($ArArr as $articleRow) {
                    $MgArticle = new Magazineissuearticle();
                    $MgArticle->m_id = $request->m_id;
                    $MgArticle->a_id = $articleRow;
                    $MgArticle->m_f = '1';
                    $MgArticle->m_lw = '0';
                    $MgArticle->m_eicn = '0';
                    $MgArticle->status = '1';
                    $MgArticle->channel_id = $request->channel_id;
                    $MgArticle->save();
                }
            }
        }
       if (isset($_POST['m_lw'])) {
            $mgalwarrd = Magazineissuearticle::where('a_id', $request->m_lw)->first();
        if(count($mgalwarrd)> 0){
            $MgArticleup = Magazineissuearticle::find($mgalwarrd->id);
            $MgArticleup->m_lw = '1';
            $MgArticleup->status = '1';
            $MgArticleup->update();
         }else{
            $MgArticle = new Magazineissuearticle();
            $MgArticle->m_id = $request->m_id;
            $MgArticle->a_id = $request->m_lw;
            $MgArticle->m_f = '0';
            $MgArticle->m_lw = '1';
            $MgArticle->m_eicn = '0';
            $MgArticle->status = '1';
            $MgArticle->channel_id = $request->channel_id;
            $MgArticle->save();

            }
        }
        if (isset($_POST['m_eicn'])) {
        $mgalwarr = Magazineissuearticle::where('a_id', $request->m_eicn)->first();
        if(count($mgalwarr)> 0){
            $MgArticleup = Magazineissuearticle::find($mgalwarr->id);
            $MgArticleup->m_eicn = '1';
            $MgArticleup->status = '1';
            $MgArticleup->update();
         }else{
            $MgArticle = new Magazineissuearticle();
            $MgArticle->m_id = $request->m_id;
            $MgArticle->a_id = $request->m_eicn;
            $MgArticle->m_f = '0';
            $MgArticle->m_lw = '0';
            $MgArticle->m_eicn = '1';
            $MgArticle->status = '1';
            $MgArticle->channel_id = $request->channel_id;
            $MgArticle->save();

            }
        }
        return  'success' ;
    }
    



}
