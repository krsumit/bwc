<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use App\Photo;
use App\Video;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\PhotosController;
use App\Http\Requests;
use App\Right;
use App\Newsletter;
use App\NewsletterArticles;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class MasternewsletterController extends Controller {

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
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $rightId = 83;
        /* Right mgmt start */
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $newsletters = DB::table('master_newsletter')->where('is_deleted', '0')->where('channel_id', $currentChannelId)->orderBy('created_at', 'DESC')->paginate(config('constants.recordperpage'));
        return view('maternewsletter.list', compact('newsletters', 'channels', 'currentChannelId'));
    }

    /**
     * Show the form for creating a new resource. - FB Interface
     *
     * @return Response
     */
    public function create() {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        /* Right mgmt start */
        $rightId = 84;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        return view('maternewsletter.create', compact('channels'));
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
        $rightId = 84;
        $currentChannelId = $request->channel_sel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $allowedChannels = $this->rightObj->getAllowedChannels($rightId);
        if (!trim($request->title)) {
            $channel = DB::table('channels')->select('channel')->where('channel_id', $currentChannelId)->first();
            $title = $channel->channel . date('-Y-M-d');
        } else {
            $title = $request->title;
        }

        $newsletter = new Newsletter();
        $newsletter->channel_id = $request->channel_sel;
        $newsletter->title = $title;
        $newsletter->save();
        Session::flash('message', 'Your Newsletter has been added successfully.');
        return redirect('/newsletter?channel=' . $currentChannelId);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        // echo $_GET['margin']; exit;
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $newsletter = Newsletter::find($id);

        /* Right mgmt start */
        $rightId = 83;
        $currentChannelId = $newsletter->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        
         $margin=1;
        if(isset($_GET['margin'])){
            $margin=$_GET['margin'];
        }
        $margin=110;
        
        /* Right mgmt end */
        $latestArticles = DB::table('articles')
                        ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                        ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id')
                        ->select(DB::raw('articles.article_id,articles.title,articles.article_id,articles.publish_date,articles.publish_time,
                    group_concat(authors.name) as name'))
                        ->where('status', 'P')
                        ->where('articles.channel_id',$currentChannelId)
                        ->whereRaw("concat(publish_date,' ',publish_time)>= now() - INTERVAL ? DAY",[$margin])
                        ->whereRaw("articles.article_id not in (select article_id from master_newsletter_articles where master_newsletter_id=$id and is_deleted=0)")
                        ->groupBy('articles.article_id')
			->orderBy('articles.publish_date','DESC')
			->orderBy('articles.publish_time','DESC')->get();	

        $assignedArticles = DB::table('articles')
                ->join('master_newsletter_articles', 'articles.article_id', '=', 'master_newsletter_articles.article_id')
                ->Leftjoin('article_author', 'articles.article_id', '=', 'article_author.article_id')
                ->Leftjoin('authors', 'article_author.author_id', '=', 'authors.author_id')
                ->select(DB::raw('master_newsletter_articles.id as asigned_id,articles.article_id,articles.title,articles.article_id,articles.publish_date,articles.publish_time,
                    group_concat(authors.name) as name'))
                ->where('master_newsletter_articles.master_newsletter_id', $id)
                ->where('master_newsletter_articles.is_deleted', '0')
                ->where('articles.status', 'P')
                ->groupBy('articles.article_id')
                ->orderBy('master_newsletter_articles.sequence')
		->orderBy('articles.publish_date','DESC')
		->orderBy('articles.publish_time','DESC')
                ->get();
        return view('maternewsletter.edit', compact('channels', 'newsletter', 'latestArticles', 'assignedArticles', 'currentChannelId','margin'));
    }

    /**
     * Show the form for editing the specified resource. - Ajax Get
     *
     * @param  int  $id
     * @return Response
     */
    
    /* */
    
    // Sorting articles within a newsletter
    // @param int $id , Request  as $request
    public function sortNewsletter($id,Request $request) {
        $newLetterId=$id;
        foreach($request->item as $k => $itm){
            $newsLetterArticle=NewsletterArticles::find($itm);
            $newsLetterArticle->sequence=$k+1;
            $newsLetterArticle->updated_at = date('Y-m-d H:i:s');
            $newsLetterArticle->save();
        }
        
        
        $newsletter=Newsletter::find($newLetterId);
        $newsletter->updated_at=date('Y-m-d H:i:s');
        $newsletter->update();
        
        
        if($newsletter->channel_id=='1')
            exec("/usr/bin/php /var/www/html/public/cronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='2')
             exec("/usr/bin/php /var/www/html/public/hotcronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='5')
             exec("/usr/bin/php /var/www/html/public/dscronscript/cronjob.php 'section=newsletter'");
		elseif($newsletter->channel_id=='3')
             exec("/usr/bin/php /var/www/html/public/bwsccronscript/cronjob.php 'section=newsletter'"); 
        elseif($newsletter->channel_id=='7')
             exec("/usr/bin/php /var/www/html/public/bweecronscript/cronjob.php 'section=newsletter'");      
        
    }   
    //End end of sorting newsletter
    public function assign(Request $request) {

        foreach ($request->checkItem as $articleId) {
            $newArticle = new NewsletterArticles();
            $newArticle->master_newsletter_id = $request->newsletterId;
            $newArticle->article_id = $articleId;
            $newArticle->updated_at = date('Y-m-d H:i:s');
            $newArticle->save();
        }
        
        $newsletter = Newsletter::find($request->newsletterId);
        $newsletter->updated_at=date('Y-m-d H:i:s');
        $newsletter->save();
        
        if($newsletter->channel_id=='1')
            exec("/usr/bin/php /var/www/html/public/cronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='2')
             exec("/usr/bin/php /var/www/html/public/hotcronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='5')
             exec("/usr/bin/php /var/www/html/public/dscronscript/cronjob.php 'section=newsletter'");
		elseif($newsletter->channel_id=='3')
             exec("/usr/bin/php /var/www/html/public/bwsccronscript/cronjob.php 'section=newsletter'"); 
        elseif($newsletter->channel_id=='7')
             exec("/usr/bin/php /var/www/html/public/bweecronscript/cronjob.php 'section=newsletter'");      


        //exec("/usr/bin/php /var/www/html/public/hotcron/cronjob.php 'section=newsletter'");

        Session::flash('message', 'Your article(s) assigned in newsletter.');
        return redirect('/newsletter/manage/' . $request->newsletterId);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $newsletter = Newsletter::find($request->newsletterId);

        /* Right mgmt start */
        $rightId = 83;
        $currentChannelId = $request->channel_sel;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        if (!trim($request->title)) {
            $channel = DB::table('channels')->select('channel')->where('channel_id', $currentChannelId)->first();
            $title = $channel->channel . date('-Y-M-d');
        } else {
            $title = $request->title;
        }
        
        $newsletter->channel_id = $request->channel_sel;
        $newsletter->title = $title;
        if($request->deactivate){

            $newsletter->status=0;
            Session::flash('message', 'Your Newsletter has been updated successfully.');
        }
        if($request->activate){
            $newsletter->status=1;
            Session::flash('message', 'Your Newsletter has been updated successfully.');
        }else{
            Session::flash('message', 'Your Newsletter has been updated successfully.');
        }
        $newsletter->save();
        Session::flash('message', 'Your Newsletter has been updated successfully.');
        return redirect('/newsletter?channel=' . $currentChannelId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return None
     */
    public function destroy() {
        
         if (!Session::has('users')) {
            return 'Please login first.';
        }
        
         /* Right mgmt start */
        $rightId=83;
         $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        // echo $currentChannelId.'--'.$rightId;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId)){
            return 'You are not authorized to access';
        }   
        /* Right mgmt end */
        
        
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        
        //echo $id ; exit;
        $na = NewsletterArticles::find($id);
        $na->is_deleted = 1;
        $na->updated_at = date('Y-m-d H:i:s');
        $na->save();
        
        $newsletter=Newsletter::find($na->master_newsletter_id);
        $newsletter->updated_at=date('Y-m-d H:i:s');
        $newsletter->update();
        
        if($newsletter->channel_id=='1')
            exec("/usr/bin/php /var/www/html/public/cronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='2')
             exec("/usr/bin/php /var/www/html/public/hotcronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='5')
             exec("/usr/bin/php /var/www/html/public/dscronscript/cronjob.php 'section=newsletter'");
		elseif($newsletter->channel_id=='3')
             exec("/usr/bin/php /var/www/html/public/bwsccronscript/cronjob.php 'section=newsletter'");
        elseif($newsletter->channel_id=='7')
             exec("/usr/bin/php /var/www/html/public/bweecronscript/cronjob.php 'section=newsletter'");       
        
        
        return 'success';
    }
    
    
    public function destroyNewsletter() {
        
         if (!Session::has('users')) {
            return 'Please login first.';
        }
        
         /* Right mgmt start */
        $rightId=83;
         $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        // echo $currentChannelId.'--'.$rightId;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId)){
            return 'You are not authorized to access';
        }   
        /* Right mgmt end */
        
        
        if (isset($_GET['option'])) {
            $ids = $_GET['option'];
        }
        $delArr = explode(',', $ids);
        
        foreach ($delArr as $id) { 
            $na = Newsletter::find($id);
            $na->is_deleted = 1;
            $na->updated_at = date('Y-m-d H:i:s');
            $na->save();
        }
        
        return 'success';
    }

}
