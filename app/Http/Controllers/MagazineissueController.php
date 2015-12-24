<?php

namespace App\Http\Controllers;

use App\FeatureBox;
use Illuminate\Http\Request;
use App\Magazineissue;
use DB;
use Session;
use App\Http\Controllers\VideosController;
use App\Http\Controllers\PhotosController;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class MagazineissueController extends Controller
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
     * Show the form for creating a new resource. - FB Interface
     *
     * @return Response
     */
    public function create()
    {
        //Authenticate User
        if(!Session::has('users')){
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id; 
         
        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $uid)
                ->orderBy('channel')
                ->get();
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
            $magazineissue = DB::table('magazine')
               ->select('magazine.*'  )
                ->where('magazine.valid', '=', '1')   
                ->where('magazine.title', 'LIKE', '%'.$queryed.'%')
		->paginate(10);
        }else{
        $magazineissue = DB::table('magazine')
               ->select('magazine.*'  )
                ->where('magazine.valid', '=', '1') 
                ->orderBy('publish_date_m', 'desc') 
		->paginate(10);
        }
        return view('magazineissue.index',compact('channels','magazineissue'));
    }

    /**
     * Get All Channels for the User Logged In
     *
     * @param User ID $uid
     * @return $channelArr Array
     */
    public function getUserChannels($uid){

        $channels = DB::table('channels')
            ->join('rights','rights.pagepath','=','channels.channel_id')
            ->join('user_rights', 'user_rights.rights_id','=','rights.rights_id')
            ->select('channels.*')
            ->where('rights.label', '=', 'channel')
            ->where('user_rights.user_id', '=', $uid)
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
        $magazineissue = new Magazineissue;
       $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
          $imageurl = '';

                if ($request->file('photo')) { // echo 'test';exit;
                    $file = $request->file('photo');
                    //$is_it = '1';
                    //$is_it = is_file($file);
                    //$is_it = '1';
                    $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
                    $name = $request->name;
                    //var_dump($file);
                    //$l = fopen('/home/sudipta/check.log','a+');
                    //fwrite($l,"File :".$filename." Name: ".$name);

                    $destination_path = 'uploads/';

                    //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
                    //$filename = "PHOTO";
                    $file->move($destination_path, $filename);
                    $imageurl = $filename;
                    $s3 = AWS::createClient('s3');
                    $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awmagazinedir').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                    if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                    }
                }
                                
                $magazineissue->title = $request->title;
                
                $magazineissue->channel_id = $request->channel;
                $magazineissue->imagepath = $imageurl;
                $magazineissue->publish_date_m = date('Y-m-d', strtotime($request->publish_date_m));
                $magazineissue->story1_title = $request->story1_title;                
                $magazineissue->story1_url = $request->story1_url;
                $magazineissue->story2_title = $request->story2_title;
                $magazineissue->story2_url = $request->story2_url;
                $magazineissue->story3_title = $request->story3_title; 
                $magazineissue->story3_url = $request->story3_url;
                $magazineissue->story4_title = $request->story4_title;                
                $magazineissue->story4_url = $request->story4_url; 
                $magazineissue->story5_title = $request->story5_title;                
                $magazineissue->story5_url = $request->story5_url; 
                $magazineissue->valid = '1';
                
                    
                $magazineissue->save();
                Session::flash('message', 'Your data has been successfully modify.');
        return redirect('/magazineissue');
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
     * Show the form for editing the specified resource. - Ajax Get
     *
     * @param  int  $id
     * @return Response
     */
    public function edit()
    {
      
        $uid = Session::get('users')->id; 
          if (isset($_GET['id'])) {
            $id = $_GET['id'];
            
        }
       
        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $uid)
                ->orderBy('channel')
                ->get();
        $posts = DB::table('magazine')
               ->select('magazine.*'  )
                ->where('magazine_id', '=', $id)   
		->get();
        //print_r($posts);
        //die;
        return view('magazineissue.magazineissueedite',compact('channels','posts')); 

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
       
       $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
          $imageurl = '';

                if ($request->file('photo')) { // echo 'test';exit;
                    $file = $request->file('photo');
                    //$is_it = '1';
                    //$is_it = is_file($file);
                    //$is_it = '1';
                    $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
                    $name = $request->name;
                    //var_dump($file);
                    //$l = fopen('/home/sudipta/check.log','a+');
                    //fwrite($l,"File :".$filename." Name: ".$name);

                    $destination_path = 'uploads/';

                    //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
                    //$filename = "PHOTO";
                    $file->move($destination_path, $filename);
                    $imageurl = $filename;
                    $s3 = AWS::createClient('s3');
                    $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awmagazinedir').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                    if($result['@metadata']['statusCode']==200){
                        unlink($destination_path . $filename);
                    }
                }
                                //echo 'e'; exit;
                $title = $request->title;
                
                $channel_id = $request->channel;
                if($request->photo){
                echo $imagepath = $imageurl;
                }else{
                 echo $imagepath = $request->photoname;  
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
                $valid= '1';
                $created_at=date('Y-m-d H:i:s');
                $updated_at=date('Y-m-d H:i:s');
               $postdata = ['title' => $title, 
                   'channel_id'=>$channel_id,
                   'imagepath'=>$imagepath,
                   'publish_date_m'=>$publish_date_m,'story1_title'=>$story1_title,'story1_url'=>$story1_url,'story2_title'=>$story2_title,'story2_url'=>$story2_url,'story3_title'=>$story3_title,'story3_url'=>$story3_url,'story4_title'=>$story4_title,'story4_url'=>$story4_url,'story5_title'=>$story5_title,'story5_url'=>$story5_url,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ];
                DB::table('magazine')
            ->where('magazine_id',$request->magazine_id)
            ->update($postdata);
                Session::flash('message', 'Your data has been successfully modify.');
        return redirect('/magazineissue');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return None
     */
    public function destroy()
    {
        //
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            //Magazineissue::where('magazine_id',$d)->delete();
            $valid='0';
            $deleteAl= [
			
			'valid' => $valid
			
            ];
            DB::table('magazine')
            ->where('magazine_id',$d)
            ->update($deleteAl);
            
        }
        return;
        
    }
}