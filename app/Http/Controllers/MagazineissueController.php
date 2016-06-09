<?php

namespace App\Http\Controllers;

use App\FeatureBox;
use Illuminate\Http\Request;
use App\Magazineissue;
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
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.awmagazinedir') . $filename,
                'SourceFile' => $destination_path . $filename,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
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
        return redirect('/magazineissue?channel='.$request->channel);
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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
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
        $currentChannelId = $posts[0]->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */

        return view('magazineissue.magazineissueedite', compact('channels', 'posts', 'currentChannelId'));
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
            $result = $s3->putObject(array(
                'ACL' => 'public-read',
                'Bucket' => config('constants.awbucket'),
                'Key' => config('constants.awmagazinedir') . $filename,
                'SourceFile' => $destination_path . $filename,
            ));
            if ($result['@metadata']['statusCode'] == 200) {
                unlink($destination_path . $filename);
            }
        }
        //echo 'e'; exit;
        $title = $request->title;

        $channel_id = $request->channel;
        if ($request->photo) {
            echo $imagepath = $imageurl;
        } else {
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
        $valid = '1';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $postdata = ['title' => $title,
            'channel_id' => $channel_id,
            'imagepath' => $imagepath,
            'publish_date_m' => $publish_date_m, 'story1_title' => $story1_title, 'story1_url' => $story1_url, 'story2_title' => $story2_title, 'story2_url' => $story2_url, 'story3_title' => $story3_title, 'story3_url' => $story3_url, 'story4_title' => $story4_title, 'story4_url' => $story4_url, 'story5_title' => $story5_title, 'story5_url' => $story5_url, 'valid' => $valid, 'created_at' => $created_at, 'updated_at' => $updated_at];
        DB::table('magazine')
                ->where('magazine_id', $request->magazine_id)
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
    public function destroy() {
        
         /* Right mgmt start */
        $rightId = 77;
        $currentChannelId =  $this->rightObj->getCurrnetChannelId($rightId);
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
        }
        return;
    }

}
