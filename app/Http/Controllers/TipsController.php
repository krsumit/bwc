<?php

namespace App\Http\Controllers;

use App\Tip;
use App\TipTag;
use Illuminate\Http\Request;
use App\Right;
use DB;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TipsController extends Controller
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
    public function create()
    {
        //

        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
//        $asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        //$asd = fopen("/home/sudipta/log.log", 'a+');

        
        /* Right mgmt start */
        $rightId=20;
        $currentChannelId=$this->rightObj->getCurrnetChannelId($rightId);
        $channels=$this->rightObj->getAllowedChannels($rightId);
        if(!$this->rightObj->checkRights($currentChannelId,$rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        ///$channels = TipsController::getUserChannels($uid);
        
        $tipArr = TipsController::getTipsforUser($channels);
        $tagArr = TipsController::getTagsforTips($tipArr);
        /*$tipArr = array();
        foreach ($channels as $ch) {
            $channelTips = Tip::where('channel_id', $ch->channel_id)
                        ->where('valid', '1')->get();
            $tipArr[$ch->channel_id] = $channelTips;
            fwrite($asd, " Channel ID ::" .$ch->channel_id  . " Values of Arr: ".count($tipArr)." Data: ".$channelTips. "\n\n");
        }
        */
        $tiptags = TipTag::where('valid','1')->get();
        $tipcategory = DB::table('tipcategory')->where('valid','1')->get();

//        fwrite($asd, " Values of Arr: ".count($tipArr)." Data: \n\n");
  //      fclose($asd);
        return view('tips.tips', compact('channels','uid','tipArr','tipcategory','tiptags','tagArr'));
    }

    /**
     * Get Tips of Each Channel in the Array
     *
     * @param Channels Array
     * @return Array
     */
    public function getTipsforUser($channels){
    //    $asd = fopen("/home/sudipta/log.log", 'a+');
        $tipArr = array();

        foreach ($channels as $ch) {
            $channelTips = Tip::where('channel_id', $ch->channel_id)
                ->join('tipcategory', 'tips.t_category_id', '=', 'tipcategory.tcate_id')
                ->where('tips.valid', '1')
                ->select('tips.*', 'tipcategory.tcategory')
                ->get();

            $tipArr[$ch->channel_id] = $channelTips;
           // fwrite($asd, " Channel ID ::" .$ch->channel_id  . " Values of Arr: ".count($tipArr)." Data: ".$channelTips. "\n\n");

        }

        return $tipArr;
    }
    /**
     * Get Tag Labels for Across Channel, Tip Records
     *
     */
    public function getTagsforTips($tipArr)
    {
      //  $asd = fopen("/home/sudipta/log.log", 'a+');
        $tagArr = array();
        $ttag = array();
        foreach($tipArr as $t) {
            foreach ($t as $c) {
                $arrTs = explode(',', $c->t_tags);
                $tagStr = '';
                foreach ($arrTs as $a) {
        //            fwrite($asd, " TTAG ID: " . $a . " \n\n");
                    $ttag = TipTag::find($a);
                    $tagStr .= $ttag->tag . ", ";
          //          fwrite($asd, " TTAG LABEL: " . $ttag->tag . " LABEL \n\n");
                }
                $tagStr = substr($tagStr, 0, -2);
                $tagArr[$c->tip_id] = $tagStr;
            }
        }
        //fclose($asd);

        return $tagArr;
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
        //
        $uid = $request->user()->id;

        //print_r($_POST);

        //If Tip is being Edited - Or if New being saved
        if($request->tid){
            $Tip = Tip::find($request->tid);
        }else {
            $Tip = new Tip();
            $Tip->add_date = date('Y-m-d H:i:s');
        }

        $Tip->tip = $request->tip;
        $Tip->description = $request->description;
        $Tip->t_category_id = $request->t_category;
        $Tip->channel_id = $request->channel_sel;
        $Tip->valid = '1';
        $tiptag = '';
        //Serializing TipTags
        foreach($request->tiptag as $tt){
            $tiptag.=$tt.",";
        }
        $tiptag = substr($tiptag,0,-1);
        $Tip->t_tags = $tiptag;

        $Tip->save();

        //If Tip is Edited -

        $channels = TipsController::getUserChannels($uid);

        $tipArr = TipsController::getTipsforUser($channels);
        $tiptags = TipTag::where('valid','1')->get();
        $tipcategory = DB::table('tipcategory')->where('valid','1')->get();
        $tagArr = TipsController::getTagsforTips($tipArr);

        return view('tips.tips', compact('channels','uid','tipcategory','tipArr','tiptags','tagArr'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show()
    {
        //Get Tips for channel ID selected
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $channelTips = Tip::where('channel_id',$id)
                ->where('valid','1')->get();
        foreach($channelTips as $c => $v){
          //  fwrite($asd, " Loop Channel ::" .$c  . " Values of Arr: ".count($v)." Data: ".$v. "\n\n");
        }

        //fwrite($asd, " Channel ID ::" .$id  . " Values of Arr: ".count($channelTips)." Data: ".$channelTips. "\n\n");
        //fclose($asd);
        return $channelTips;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  None
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
        $editTip = Tip::where('tip_id',$id)
                    ->join('tipcategory','tips.t_category_id','=','tipcategory.tcate_id')
                    ->select('tips.*','tipcategory.tcategory')
                    ->get();

        //foreach($channelTips as $c){
        //fwrite($asd, " Total Array TTAG: ".$editTip[0]." \n\n");
        $ttag = array();
        $arrTs = explode(',',$editTip[0]->t_tags);
        foreach($arrTs as $a) {
          //  fwrite($asd, " TTAG ID: ".$a." \n\n");
            $ttag[] = TipTag::find($a);
          //  fwrite($asd, " TTAG Data: ".$ttag[0]->tag." Data \n\n");
        }

        //}
        echo json_encode(array($editTip, $ttag));
        //return ($editTip, $ttag) ;
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
        $Tip = TipsController::find($id);

        $Tip->tip = $request->tip;
        $Tip->description = $request->description;
        $Tip->t_category_id = $request->t_category;
        $Tip->channel_id = $request->channel_sel;
        $Tip->add_date = date('Y-m-d H:i:s');
        $Tip->valid = '1';
        $tiptag = '';
        //Serializing TipTags
        foreach($request->tiptag as $tt){
            $tiptag.=$tt.":";
        }
        $tiptag = substr($tiptag,0,-1);
        $Tip->t_tags = $tiptag;

        $Tip->save();

        //return $editTip;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int(serialized string)  $id
     * @return None
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
            $deleteTip = Tip::find($d);
            $deleteTip->valid = 0;
            $deleteTip->save();
        }
        return;
    }
}
