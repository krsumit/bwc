<?php

namespace App\Http\Controllers;

use App\Author;
use App\Tag;
use Illuminate\Http\Request;

use DB;
use Session;
use App\QuickByte;
use App\Http\Requests;
use App\Http\Controllers\Auth;
use App\Http\Controllers\AuthorsController;
use App\Http\Controllers\Controller;
use App\Photo;
use App\QuickbyteCategory;
use App\Classes\Zebra_Image;
use App\Classes\UploadHandler;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;
class QuickBytesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($option)
    {
        //echo count($arr);exit;
        //
        if(!Session::has('users')){
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch($option){
            case "published":
                $status = 'P';
                $rightLabel = "publishedQBs";
                break;
            case "deleted":
                $status = 'D';
                $rightLabel = "deletedQBs";
                break;
        }
         //Get QB Array
         //DB::enableQueryLog();
        
        $q = QuickByte::where('status',$status)
                ->select('quickbyte.id','quickbyte.title','quickbyte.publish_date','photos.photopath')
                ->leftJoin('photos',function($leftjoin){
                    $leftjoin->on('quickbyte.id','=','photos.owner_id')
                            ->where('photos.owned_by','=','quickbyte');
                        
                });
                //->leftJoin(DB::raw('count(*) as user_count'), 'photos','quickbyte.id','=','photos.owner_id')
                /*->where(function($qbytes){
                    $qbytes->whereNull('photos.owned_by')->orWhere('photos.owned_by','quickbyte') ;
                }) ;*/
        
            if (isset($_GET['searchin'])) {
                if ($_GET['searchin'] == 'title') {
                    $q->where('quickbyte.title', 'like', '%' . $_GET['keyword'] . '%');
                }
                if (@$_GET['searchin'] == 'id') {
                    $q->where('quickbyte.id', $_GET['keyword']);
                }
            }



        $qbytes=$q->groupby('quickbyte.id')->paginate(config('constants.recordperpage'));
        //qbytes = QuickByte::where('status',$status)->get();
        
        //$query = DB::getQueryLog();
        //$lastQuery = end($query);
       // print_r($lastQuery);exit;
        //echo count($qbytes);exit;
        $arrRights = QuickBytesController::getRights($uid);
        
        foreach($arrRights as $eachRight) {
            if ($rightLabel == $eachRight->label){
               // echo json_encode($qbytes);exit;
                return view('quickbytes.'.$option, compact('qbytes'));
            }
        }

        return redirect('/dashboard');

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
        $channels = QuickBytesController::getUserChannels($uid);
        $authors = Author::where('author_type_id','=',2)->get();
        $category = DB::table('category')->where('valid','1')->orderBy('name')->get();
        
        $tags = Tag::where('valid','1')->get();
        $p1= DB::table('author_type')->where('valid','1')->whereIn('author_type_id',[1,2])->lists('label','author_type_id');
        //fclose($asd);
        return view('quickbytes.create', compact('category','uid','channels','p1','authors','tags'));
    }
    /*
     * Get Page Rights of the User
     */
    public function getRights($uid, $parentId=0){

        $rights = DB::table('rights')
        ->join('user_rights','user_rights.rights_id','=','rights.rights_id')
        ->where('user_rights.user_id','=',$uid)
        ->where(function($rts) use ($parentId){
                    $rts->where('rights.parent_id','=',0)->orwhere('rights.parent_id','=',$parentId) ;
          })
        ->get();

        return $rights;
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
            ->orderBy('channel')
            ->get();

        return $channels;
    }
    
    
    function imageUpload(){
      //  echo 'test';exit;
        $arg['script_url']=url('quickbyte/image/upload');
        $upload_handler = new UploadHandler($arg);
    }
    

    public function uploadImg(Request $request){

        //$asd = fopen("/home/sudipta/log.log", 'a+');
        //fwrite($asd, "Upload Images:".var_dump($_POST)." \n");
        //fclose($asd);
        return;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    { 
		 if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        //Session's User Id
      // echo count($request->Ltopics);
        $uid = $request->user()->id;
        //fwrite($asd, "Step 3.2 In Article POST Function ".$uid." \n");
       // echo '<pre>';
       // print_r($request->all());exit;

         $quickbyte = new QuickByte();

        // Add Arr Data to Article Table //
        $quickbyte->channel_id = $request->channel;
        $quickbyte->author_type = $request->author_type;
        if($request->author_type==1)
            $quickbyte->author_id = 1;
        else 
        $quickbyte->author_id = $request->author_name;
        $quickbyte->title = $request->title;
        $quickbyte->description = $request->featuredesc;
        $quickbyte->tags = $request->Taglist;
        $quickbyte->topics = (count($request->Ltopics)>0)?implode(',',$request->Ltopics):'';
        $quickbyte->sponsored = ($request->is_sponsored)?'1':'0';
        $quickbyte->add_date = date('Y-m-d H:i:s');
        $quickbyte->publish_date = date('Y-m-d H:i:s');
        $quickbyte->status = 'P';
        $quickbyte->created_at = date('Y-m-d H:i:s');
        $quickbyte->updated_at = date('Y-m-d H:i:s');
        //$article->publish_date = 0;//$request->datepicked;
        //$article->publish_time = 0;//$request->timepicked;

//        $article->for_homepage = $request->for_homepage ? 1 : 0;
//        $article->important = $request->important ? 1 : 0;
//        $article->web_exclusive = $request->web_exclusive ? 1 : 0;
//
//        $article->slug = 'slug';
//        $article->status = $request->status;

        $quickbyte->save();
        $id = $quickbyte->id;
        //Get Article_id
       
         
          //Quickbyte Category - Save
            for ($i = 1; $i <= 4; $i++) {
                $quick_category = new QuickbyteCategory();
                $quick_category->quickbyte_id = $id;
                $label = "category" . $i;
                if ($request->$label == '') {
                    break;
                }
                $quick_category->category_id = $request->$label;
                $quick_category->category_level = $i;
                $quick_category->save();
            }
            
         
            
        $images = explode(',', $request->uploadedImages);
       
            //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
            $c=0;
            $s3 = AWS::createClient('s3');
            foreach ($images as $image) {
                $source=$_SERVER['DOCUMENT_ROOT'].'/files/'.$image;
                $source_thumb=$_SERVER['DOCUMENT_ROOT'].'/files/thumbnail/'.$image;
                $dest=$_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbyteimagedir').$image;
                if(@copy($source,$dest)){
                     $imaged = new Zebra_Image();

                        // indicate a source image
                        $imaged->source_path = $dest;
                         $imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimagethambtdir') . $image;
                        $imaged->preserve_aspect_ratio = false; 
                        if ($imaged->resize(90, 63, ZEBRA_IMAGE_BOXED, -1)) {
                            $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimagethumbtdir') . $image,
                                'SourceFile' => $imaged->target_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->target_path);
                            }
                        }
                        //$imaged->source_path = $dest;
                         $imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimagemediumdir') . $image;
                        $imaged->preserve_aspect_ratio = true;
                         if ($imaged->resize(349, 219, ZEBRA_IMAGE_BOXED, -1)){
                             $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimagemediumdir') . $image,
                                'SourceFile' => $imaged->target_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->target_path);
                            }
                         }
                        //$imaged->source_path = $dest;
                        // $imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimageextralargedir') . $image;
                         
                         //if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)){
                             $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimageextralargedir') . $image,
                                'SourceFile' => $imaged->source_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->source_path);
                            }
                         //}
                         
                        unlink($source);
                        unlink($source_thumb);
                        $imageEntry=new Photo();
                        $imageEntry->title=$request->imagetitle[$c];
                        $imageEntry->description=$request->imagedesc[$c];;
                        $imageEntry->photopath=$image;
                        $imageEntry->imagefullPath='';
                        $imageEntry->channel_id=$request->channel;
                        $imageEntry->owned_by='quickbyte';
                        $imageEntry->owner_id=$id;
                        $imageEntry->active='1';
                        $imageEntry->created_at=date('Y-m-d H:i:s');
                        $imageEntry->updated_at=date('Y-m-d H:i:s');
                        $imageEntry->save();
                        $c++;
                }
        
            }
             
        //If has been Saved by Editor
           
        if($request->status == 'P') {
            Session::flash('message', 'Your Quickbte has been Published successfully.');
            return redirect('/quickbyte/list/published');
        }
      
//        if($request->status == 'D') {
//            Session::flash('message', 'Your Article has been Saved successfully.');
//        }
//       
//        //fclose($asd);
//        return redirect('/quickbyte/list/published');

        //return redirect('/dashboard');
        //return Redirect::to('/dashboard');
        //return view('/dashboard');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
		 if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        
        $quickbyte=QuickByte::find($id);
        //print_r($quickbyte);
//exit;       //$arq=  explode($quickbyte->topics);exit;
        $arrTopics = DB::table('topics')
                    ->whereIn('id',explode(',', $quickbyte->topics))
                    ->select('id','topic')
                    ->get();
        //echo '<pre>';
        $photos=DB::table('photos')
                ->where('owned_by','quickbyte')
                ->where('valid','1')
                ->where('owner_id',$id)->get();
        //print_r($photos);exit;
       $tags=  json_encode(DB::table('tags')
                ->select('tags_id as id','tag as name')
                ->whereIn('tags_id',explode(',',$quickbyte->tags))->get());
        $uid = Session::get('users')->id;
        $channels = QuickBytesController::getUserChannels($uid);
        $authors = Author::where('author_type_id','=',$quickbyte->author_type)->get();
        $p1= DB::table('author_type')->where('valid','1')->whereIn('author_type_id',[1,2])->lists('label','author_type_id');
        //Quickbytecategory 
        $acateg2 = DB::table('quickbyte_category')->where('quickbyte_id','=',$id)->get();
        $cateStr = array();
        $acateg = array();
        foreach($acateg2 as $ac) {
            $lable = 'c' . $ac->category_level;
            $cateStr[$lable] = $ac->category_id;            
            //fwrite($asd, " Category Level ::" . $ac->level . " \n");            
            switch ($ac->category_level) {
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
                    $acateg[1]['name'] = $catlbl[0]->name;;
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
                    $acateg[3]['name'] = $catlbl[0]->name;;
                    break;
            }
            
        }
         $category = DB::table('category')->where('valid','1')->orderBy('name')->get();
        
        
        return view('quickbytes.edit', compact('quickbyte','arrTopics','photos','tags','channels','authors','p1','acateg','category'));
        // print_r($phots);exit;
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request)
    {	 if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
		//QuickByte
        //print_r($request->all());exit;
        $quickbyte = QuickByte::find($request->id);
        // Add Arr Data to Article Table //
        $quickbyte->channel_id = $request->channel;
        $quickbyte->author_type = $request->author_type;
        $quickbyte->author_id = $request->author_name;
        $quickbyte->title = $request->title;
        $quickbyte->description = $request->featuredesc;
        $quickbyte->tags = $request->Taglist;
        $quickbyte->topics = (count($request->Ltopics)>0)?implode(',',$request->Ltopics):'';
        $quickbyte->sponsored = ($request->is_sponsored)?'1':'0';
        //$quickbyte->add_date = date('Y-m-d H:i:s');
        //$quickbyte->publish_date = date('Y-m-d H:i:s');
        $quickbyte->status = $request->status;
        //$quickbyte->created_at = date('Y-m-d H:i:s');
        $quickbyte->updated_at = date('Y-m-d H:i:s');
        $quickbyte->update();
        
        $id = $request->id;
        
        
        //Quickbyte Category - Save New: Delete Old
       // $arrExistingCats =
                DB::table('quickbyte_category')->where('quickbyte_id','=',$id)->delete();
//        if(count($arrExistingCats)>0){
//            foreach($arrExistingCats as $eachCat) {
//                //fwrite($asd, " Each Cat Being Deleted : ".$eachCat->a_category_id."  \n");
//                $delCat = QuickbyteCategory::find($eachCat->a_category_id);
//                $delCat->delete();
//            }
//        }
        //Quickbyte Category - Save
        for ($i = 1; $i <= 4; $i++) {
            $quick_category = new QuickbyteCategory();
            $quick_category->quickbyte_id = $id;
            $label = "category" . $i;
            if ($request->$label == '') {
                break;
            }
            $quick_category->category_id = $request->$label;
            $quick_category->category_level = $i;
            $quick_category->save();
        }
        
        
        
        
        
        
        $images = explode(',', $request->uploadedImages);
            //fwrite($asd, "Each Photo Being Updated".count($arrIds)." \n");
            $c=0;
            $s3 = AWS::createClient('s3');
            foreach ($images as $image) {
                $source=$_SERVER['DOCUMENT_ROOT'].'/files/'.$image;
                $source_thumb=$_SERVER['DOCUMENT_ROOT'].'/files/thumbnail/'.$image;
                $dest=$_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbyteimagedir').$image;
                if(@copy($source,$dest)){
                     $imaged = new Zebra_Image();

                        // indicate a source image
                        $imaged->source_path = $dest;
                         $imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimagethambtdir') . $image;
                        $imaged->preserve_aspect_ratio = false; 
                        if ($imaged->resize(90,63, ZEBRA_IMAGE_BOXED, -1)) {
                            $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimagethumbtdir') . $image,
                                'SourceFile' => $imaged->target_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->target_path);
                            }
                        }
                        //$imaged->source_path = $dest;
                         $imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimagemediumdir') . $image;
                        $imaged->preserve_aspect_ratio = true;
                         if ($imaged->resize(349, 219, ZEBRA_IMAGE_BOXED, -1)) {
                               $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimagemediumdir') . $image,
                                'SourceFile' => $imaged->target_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->target_path);
                            }
                         }
                        //$imaged->source_path = $dest;
                         //$imaged->target_path = $_SERVER['DOCUMENT_ROOT'].'/'.config('constants.quickbytesimageextralargedir') . $image;
                         //if ($imaged->resize(680, 450, ZEBRA_IMAGE_BOXED, -1)){
                              $result = $s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket' => config('constants.awbucket'),
                                'Key' => config('constants.awquickbytesimageextralargedir') . $image,
                                'SourceFile' => $imaged->source_path,
                            ));
                            if ($result['@metadata']['statusCode'] == 200) {
                                unlink($imaged->source_path);
                            }
                         //}
                         
                        unlink($source);
                        unlink($source_thumb);
                       // unlink($dest);
                        $imageEntry=new Photo();
                        $imageEntry->title=$request->imagetitle[$c];
                        $imageEntry->description=$request->imagedesc[$c];;
                        $imageEntry->photopath=$image;
                        $imageEntry->imagefullPath='';
                        $imageEntry->channel_id=$request->channel;
                        $imageEntry->owned_by='quickbyte';
                        $imageEntry->owner_id=$id;
                        $imageEntry->active='1';
                        $imageEntry->created_at=date('Y-m-d H:i:s');
                        $imageEntry->updated_at=date('Y-m-d H:i:s');
                        $imageEntry->save();
                        $c++;
                }
        
            }
        //If has been Saved by Editor
        if($request->status == 'P') {
            Session::flash('message', 'Your Quickbte has been Published successfully.');
            return redirect('/quickbyte/list/published');
        }
      
        if($request->status == 'D') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/quickbyte/list/deleted');
        }
       
        //fclose($asd);
       // return redirect('/quickbyte/list/published');

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
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $deleteQB = QuickByte::find($d);
            $deleteQB->status = 'D';
            $deleteQB->save();
        }
        return;
    }
     public function publishBulk(){
         if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }
         $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $qb = QuickByte::find($d);
            $qb->status = 'P';
            $qb->save();
        }
        return;
    }
    
}
