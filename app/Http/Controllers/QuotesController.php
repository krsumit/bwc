<?php

namespace App\Http\Controllers;

use App\Quote;
use Illuminate\Http\Request;
use App\Right;
use DB;
use PhpParser\Node\Expr\Array_;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
use App\Classes\FileTransfer;
class QuotesController extends Controller
{
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
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
//        $asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        //$asd = fopen("/home/sudipta/log.log", 'a+');

         /* Right mgmt start */
        $rightId=34;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        //$channels = QuotesController::getUserChannels($uid);
        $quoteArr='';
         //B::enableQueryLog();
        if($request->channel){
            $query=Quote::where('channel_id', $request->channel)
                ->select('quotes.*','quotetags.tag')
                ->join('quotetags','quotes.q_tags','=','quotetags.tag_id')    
                ->where('quotes.valid', '1');
            if($request->keyword){
                $keyword=$request->keyword;
                $query->where(function($q) use ($keyword) {
                    $q->where('quotes.description', 'like', '%' .trim($keyword) . '%')
                      ->orWhere('quotetags.tag', 'like', '%' .trim($keyword) . '%');
                });
                
            }
           $quoteArr=$query->paginate(config('constants.recordperpage'));
           
//                   $query = DB::getQueryLog();
//        $lastQuery = end($query);
//        print_r($lastQuery);exit;
            //echo count($quoteArr);exit;
        }else{
            //echo 'test1'; exit;
        }
        //$quotetags = DB::table('quotetags')->where('valid','1')->get();
        //$qtauthor = DB::table('quotescategory')->where('valid','1')->get();
       // print_r($qtauthor);exit;
        
        //$quoteArr = QuotesController::getQuotesforUser($channels);
        //$tagArr = QuotesController::getTagsforQuotes($quoteArr);

  //      fwrite($asd, " Values of Q Arr: ".count($quoteArr)." Data: \n\n");
    //    fclose($asd);
        //        return view('tips.quotes', compact('uid','channels','quoteArr','qtauthor','quotetags','tagArr'));

        return view('tips.quotes', compact('uid','channels','quoteArr'));
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
     * Get Tag Labels for Across Channel, Quote Records
     *
     * @param Quotes Array
     * @return Tags Array
     */
    public function getTagsforQuotes($quoteArr)
    {
      //  $asd = fopen("/home/sudipta/log.log", 'a+');
        $tagArr = array();
        $qtag = array();
        foreach($quoteArr as $q) {
            foreach ($q as $c) {
                $arrTs = explode(',', $c->q_tags);
                $tagStr = '';
                foreach ($arrTs as $a) {
                    //fwrite($asd, " QTAG ID: " . $a . " \n\n");
                    $qtag = DB::table('quotetags')->where('tag_id','=',$a)->get();
                    foreach($qtag as $TT => $v) {
                        //fwrite($asd, " QTAG ARR: " . $TT . " Value: ".$v->tag." \n\n");
                        $tagStr .= $v->tag . ", ";
                    }
                }
                $tagStr = substr($tagStr, 0, -2);
                //fwrite($asd, " Final tag Arr: " . $tagStr . " \n\n");
                $tagArr[$c->quote_id] = $tagStr;
            }
        }
        //fclose($asd);

        return $tagArr;
    }
    /**
     * Get Quotes of Each Channel in the Array
     *
     * @param Channels Array
     * @return Array
     */
//    public function getQuotesforUser($channels){
//        //$asd = fopen("/home/sudipta/log.log", 'a+');
//        $tipArr = array();
//
//        foreach ($channels as $ch) {
//            $channelQuotes = Quote::where('channel_id', $ch->channel_id)
//                            
//                ->select('quotes.*')
//                 ->where('quotes.valid', '1')
//                ->get();
//
//            $quoteArr[$ch->channel_id] = $channelQuotes;
//            // fwrite($asd, " Channel ID ::" .$ch->channel_id  . " Values of Arr: ".count($tipArr)." Data: ".$channelTips. "\n\n");
//
//        }
//
//        return $quoteArr;
//    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
        /* Right mgmt start */
        $rightId=34;
        $currentChannelId=$request->channel_sel;
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        $uid = $request->user()->id;
        //print_r($_POST);
        if($request->qid){
            $Quote = Quote::find($request->qid);
        }else {
            $Quote = new Quote();
            $Quote->add_date = date('Y-m-d H:i:s');
        }
	if($request ->quotes_image !=''){
            $file = $request->file('quotes_image');
            $filename = str_random(6) . '_' . $request->file('quotes_image')->getClientOriginalName();
            $Quote->quotes_image = $filename;
            $fileTran = new FileTransfer();
            if(trim($request->edit_quotes_image))
                $fileTran->deleteFile(config('constants.quotesimage'),$request->edit_quotes_image);
            
            $fileTran->uploadFile($file, config('constants.quotesimage'), $filename);
                
        }else{
         $Quote->quotes_image = $request->edit_quotes_image;  
        }
        
        $Quote->quote = 'quote';
        $Quote->description = $request->description;
        $Quote->q_category_id = 'category';
        $Quote->channel_id = $request->channel_sel;
        
        $Quote->valid = '1';
        $Quote->q_tags = $request->Taglist;

        $Quote->save();

        return redirect($_SERVER['HTTP_REFERER']);
        // To Reload Quote Page -
//        $channels = QuotesController::getUserChannels($uid);
//        $quoteArr = QuotesController::getQuotesforUser($channels);
//        $qtauthor = DB::table('quotescategory')->where('valid','1')->get();
//        $tagArr = QuotesController::getTagsforQuotes($quoteArr);
//        $quotetags = DB::table('quotetags')->where('valid','1')->get();
//
//        return view('tips.quotes', compact('uid','channels','quoteArr','qtauthor','quotetags','tagArr'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
        //
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //fwrite($asd, " EDIT ID Passed ::" .$id  . "\n\n");
        $editQuote = Quote::where('quote_id',$id)
            ->select('quotes.*')
            ->get();

        //fwrite($asd, " Total Array QTAG: ".$editQuote[0]." \n\n");
        $qtag = array();
        $q1tag = array();
        $arrTs = explode(',',$editQuote[0]->q_tags);
        foreach($arrTs as $a) {
            //fwrite($asd, " QTAG ID: ".$a." \n\n");
            $q1tag = DB::table('quotetags')->where('tag_id','=',$a)->get();
            foreach($q1tag as $TT => $v) {
                //fwrite($asd, " QTAG ARR: " . $v->tag_id . " Value: ".$v->tag." \n\n");
                //$tagStr .= $v->tag . ", ";
                $qtag[] = array('id'=>$v->tag_id,'name'=>$v->tag);
            }
            //fwrite($asd, " TTAG Data: ".$qtag[0]->tag_id." Data \n\n");
        }
        $editQuote[0]->tag=$qtag;
        echo json_encode(array($editQuote));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
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
          //  fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteQuote = Quote::find($d);
            $deleteQuote->valid = 0;
            $deleteQuote->save();
        }
        return;
    }
}
