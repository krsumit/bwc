<?php

namespace App\Http\Controllers;

use App\TipTag;
use Illuminate\Http\Request;

use DB;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class TipTagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
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
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;

        $tiptagArr = TipTag::where('valid','1')->get();
        //fclose($asd);

        return view('tips.tiptag', compact('tiptagArr','uid'));
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
        $uid = $request->id;

        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //print_r($_POST);
        //exit;

        //If TipTag is being Edited - Or if New being saved
        if($request->tid){
            $TipTag = TipTag::find($request->tid);
        }else {
            $TipTag = new TipTag();
        }
        $matchText= $request->ttag;
        $rstcount = DB::table('tiptags')->where('tag', "=", $matchText )->select('ttag_id as id', 'tag as name')->count();
       if($rstcount>0){
             Session::flash('message', 'This tag already seem to exist! Please check list.');
           
        }else{
            $TipTag->tag = $matchText; 
            $TipTag->sponsored_by = $request->sponsoredby;
            if($request->logofile !='') {
                $destination_path = 'uploads/';
                $filename = str_random(6) . '_' . $request->file('logofile')->getClientOriginalName();
                //fwrite($asd, " File name:".$filename."  \n");
                $request->file('logofile')->move($destination_path, $filename);
                $TipTag->logopath = $destination_path . $filename;
            }
            $TipTag->url = $request->url;
            $TipTag->save();
        }
        $tiptagArr = TipTag::where('valid','1')->get();

    //fclose($asd);

        return view('tips.tiptag', compact('tiptagArr','uid'));
        
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
  //      $asd = fopen("/home/sudipta/log.log", 'a+');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
    //    fwrite($asd, " TipTag EDIT ID Passed ::" .$id  . "\n\n");
        $editTipTag = TipTag::find($id);


        //foreach($channelTips as $c){
      //  fwrite($asd, " TipTag Array : ".$editTipTag." \n\n");

        return $editTipTag;
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
    public function destroy($id)
    {
        //
    }
}
