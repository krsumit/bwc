<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Country;
use App\State;
use App\Event;
use App\Article;
use App\LiveFeed;
use App\SpeakerTag;
use App\Speaker;
use App\SpeakerDetails;
use App\ActivityLog;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class LiveFeedController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->rightObj = new Right();
    }

    public function index() {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
      
    }

    public function store(Request $request) {
        //dd($request);
        $rightId = 10;
        $article=Article::find($request->article_id);
        $currentChannelId=$article->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
   
        $validation = $this->validate($request, [
            'description' => 'required'
        ]);
   //dd($request);
        $liveFeed = new LiveFeed();
        $liveFeed->article_id = trim($request->article_id);
        $liveFeed->title = trim($request->title);
        $liveFeed->description = $request->description;
        $liveFeed->save();
   
        Session::flash('message', 'Feed added successfully.');
        return Redirect::to('livefeed/'.$request->article_id);
    }

  
    public function edit($id){       
        $rightId = 10;
        $feed=  LiveFeed::find($id);
        $article=Article::find($feed->article_id);
        $currentChannelId=$article->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $liveFeeds=LiveFeed::where('article_id','=',$feed->article_id)->where('id','!=',$id)->orderBy('updated_at','desc')->get(); 
        
        return view('livefeed.edit',compact('article','liveFeeds','feed'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
        $rightId = 10;
        $article=Article::find($request->article_id);
        $currentChannelId=$article->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
   
        $validation = $this->validate($request, [
            'description' => 'required'
        ]);
   //dd($request);
        $liveFeed = LiveFeed::find($request->feed_id);
        $liveFeed->article_id = trim($request->article_id);
        $liveFeed->title = trim($request->title);
        $liveFeed->description = $request->description;
        $liveFeed->save();
   
        Session::flash('message', 'Feed updated successfully.');
        return Redirect::to('livefeed/'.$request->article_id);
         
    }
    
    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        // echo 'test'; exit;
        $rightId = 10;
        $article=Article::find($id);
        $currentChannelId=$article->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $liveFeeds=LiveFeed::where('article_id','=',$id)->orderBy('updated_at','desc')->get(); 
        return view('livefeed.create',compact('article','liveFeeds'));
    }
  
    
    public function destroy(Request $request){
        //dd($request);
        LiveFeed::whereIn('id',$request->checkItem)->delete();
        Session::flash('message', 'Feed deleted successfully.');
        return Redirect::to('livefeed/'.$request->article_id);
    }
    

}
