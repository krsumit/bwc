<?php
namespace App\Http\Controllers;
use App\Author;
use App\Tag;
use Illuminate\Http\Request;
use App\Right;
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
use App\Classes\FileTransfer;


class QuickBytesController extends Controller {

    private $rightObj;

    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($option) {
        //echo 'test';exit;
        //
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $uid = Session::get('users')->id;
        $rightLabel = "";
        switch ($option) {
            case "published":
                $status = 'P';
                $rightId = '24';
                //$rightLabel = "publishedQBs";
                break;
            case "deleted":
                $status = 'D';
                $rightId = '25';
                // $rightLabel = "deletedQBs";
                break;
        }

        /* Right mgmt start */
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */


        $q = QuickByte::where('status', $status)
                ->select('quickbyte.id', 'quickbyte.title', 'quickbyte.publish_date', 'photos.photopath')
                ->where('quickbyte.channel_id', '=', $currentChannelId)
                ->leftJoin('photos', function($leftjoin) {
            $leftjoin->on('quickbyte.id', '=', 'photos.owner_id')
            ->where('photos.owned_by', '=', 'quickbyte');
        });
        //->leftJoin(DB::raw('count(*) as user_count'), 'photos','quickbyte.id','=','photos.owner_id')
        /* ->where(function($qbytes){
          $qbytes->whereNull('photos.owned_by')->orWhere('photos.owned_by','quickbyte') ;
          }) ; */

        if (isset($_GET['searchin'])) {
            if ($_GET['searchin'] == 'title') {
                $q->where('quickbyte.title', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (@$_GET['searchin'] == 'id') {
                $q->where('quickbyte.id', $_GET['keyword']);
            }
        }



        $qbytes = $q->groupby('quickbyte.id')->orderBy('quickbyte.updated_at', 'desc')->paginate(config('constants.recordperpage'));
        //qbytes = QuickByte::where('status',$status)->get();
        //$query = DB::getQueryLog();
        //$lastQuery = end($query);
        // print_r($lastQuery);exit;
        //echo count($qbytes);exit;
        //$arrRights = QuickBytesController::getRights($uid);
//        foreach($arrRights as $eachRight) {
//            if ($rightLabel == $eachRight->label){
        // echo json_encode($qbytes);exit;
        return view('quickbytes.' . $option, compact('qbytes', 'channels', 'currentChannelId'));
//            }
//        }
        // return redirect('/dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {
        //Authenticate User
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */


        //$asd = fopen("/home/sudipta/log.log", 'a+');
        $uid = Session::get('users')->id;
        $authors = Author::where('author_type_id', '=', 2)->get();
        $category = DB::table('category')->where('channel_id', $currentChannelId)->where('valid', '1')->orderBy('name')->get();
        $campaign = DB::table('campaign')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $tags = Tag::where('valid', '1')->get();
        $p1 = DB::table('author_type')->where('valid', '1')->whereIn('author_type_id', [1, 2])->lists('label', 'author_type_id');
        //fclose($asd);
        return view('quickbytes.create', compact('category', 'uid', 'channels', 'p1', 'authors', 'tags', 'currentChannelId', 'campaign'));
    }

    /*
     * Get Page Rights of the User
     */

    public function getRights($uid, $parentId = 0) {

        $rights = DB::table('rights')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->where('user_rights.user_id', '=', $uid)
                ->where(function($rts) use ($parentId) {
                    $rts->where('rights.parent_id', '=', 0)->orwhere('rights.parent_id', '=', $parentId);
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
    public function getUserChannels($userID) {

        $channels = DB::table('channels')
                ->join('rights', 'rights.pagepath', '=', 'channels.channel_id')
                ->join('user_rights', 'user_rights.rights_id', '=', 'rights.rights_id')
                ->select('channels.*')
                ->where('rights.label', '=', 'channel')
                ->where('user_rights.user_id', '=', $userID)
                ->orderBy('channel')
                ->get();

        return $channels;
    }

    function imageUpload() {
        //  echo 'test';exit;
        $arg['script_url'] = url('quickbyte/image/upload');
        $upload_handler = new UploadHandler($arg);
    }

    public function uploadImg(Request $request) {

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
    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */


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
        if ($request->author_type == 1)
            $quickbyte->author_id = 1;
        else
            $quickbyte->author_id = $request->author_name;
        $quickbyte->title = $request->title;
        $quickbyte->description = $request->featuredesc;
        $quickbyte->tags = $request->Taglist;
        $quickbyte->topics = (count($request->Ltopics) > 0) ? implode(',', $request->Ltopics) : '';
        $quickbyte->sponsored = ($request->is_sponsored) ? '1' : '0';
        $quickbyte->add_date = date('Y-m-d H:i:s');
        $quickbyte->publish_date = date('Y-m-d H:i:s');
        $quickbyte->status = 'P';
        $quickbyte->campaign_id = $request->campaign;
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

        $fileTran = new FileTransfer();
        foreach ($images as $image) {
            if (isset($request->photographby[$image])) {
                $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
                $source = '';
                $dest = config('constants.awquickbytesimageextralargedir');
                $fileTran->tranferFile($image, $source, $dest, false);
                $destination = config('constants.awquickbytesimagethumbtdir');
                $fileTran->resizeAndTransferFile($image, '90X63', $source, $destination);
                $destination = config('constants.awquickbytesimagemediumdir');
                $fileTran->resizeAndTransferFile($image, '349X219', $source, $destination);
                if (is_file($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image))
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image);
                if (is_file($source_thumb))
                    unlink($source_thumb);
                $imageEntry = new Photo();
                $imageEntry->title = $request->imagetitle[$image];
                $imageEntry->description = $request->imagedesc[$image];
                $imageEntry->photo_by = $request->photographby[$image];
                $imageEntry->photopath = $image;
                $imageEntry->imagefullPath = '';
                $imageEntry->channel_id = $request->channel;
                $imageEntry->owned_by = 'quickbyte';
                $imageEntry->owner_id = $id;
                $imageEntry->active = '1';
                $imageEntry->created_at = date('Y-m-d H:i:s');
                $imageEntry->updated_at = date('Y-m-d H:i:s');
                $imageEntry->save();
            }
        }

        //If has been Saved by Editor

        if ($request->status == 'P') {
            Session::flash('message', 'Your Quickbte has been Published successfully.');
            return redirect('/quickbyte/list/published?channel=' . $request->channel);
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
    public function show($id) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        $quickbyte = QuickByte::find($id);

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $quickbyte->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */


        //print_r($quickbyte);
//exit;       //$arq=  explode($quickbyte->topics);exit;
        $arrTopics = DB::table('topics')
                ->whereIn('id', explode(',', $quickbyte->topics))
                ->select('id', 'topic')
                ->get();
        //echo '<pre>';
        $photos = DB::table('photos')
                        ->where('owned_by', 'quickbyte')
                        ->where('valid', '1')
                        ->where('owner_id', $id)->orderBy('sequence')->get();
        //print_r($photos);exit;
        $tags = json_encode(DB::table('tags')
                        ->select('tags_id as id', 'tag as name')
                        ->whereIn('tags_id', explode(',', $quickbyte->tags))->get());
        $uid = Session::get('users')->id;
        //$channels = QuickBytesController::getUserChannels($uid);
        $authors = Author::where('author_type_id', '=', $quickbyte->author_type)->get();
        $p1 = DB::table('author_type')->where('valid', '1')->whereIn('author_type_id', [1, 2])->lists('label', 'author_type_id');
        //Quickbytecategory 
        //echo $currentChannelId; exit;
        $acateg2 = DB::table('quickbyte_category')->where('quickbyte_id', '=', $id)->get();
        $campaign = DB::table('campaign')->where('channel_id', $currentChannelId)->where('valid', '1')->get();
        $cateStr = array();
        $acateg = array();
        foreach ($acateg2 as $ac) {
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
                    $acateg[1]['name'] = $catlbl[0]->name;
                    ;
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
                    $acateg[3]['name'] = $catlbl[0]->name;
                    ;
                    break;
            }
        }
        $category = DB::table('category')->where('channel_id', '=', $currentChannelId)->where('valid', '1')->orderBy('name')->get();


        return view('quickbytes.edit', compact('quickbyte', 'arrTopics', 'photos', 'tags', 'channels', 'authors', 'p1', 'acateg', 'category', 'campaign'));
        // print_r($phots);exit;
    //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {
        //
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
        //QuickByte
        //print_r($request->all());exit;

        /* Right mgmt start */
        $rightId = 23;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $quickbyte = QuickByte::find($request->id);
        // Add Arr Data to Article Table //
        $quickbyte->channel_id = $request->channel;
        $quickbyte->author_type = $request->author_type;
        $quickbyte->author_id = $request->author_name;
        $quickbyte->title = $request->title;
        $quickbyte->description = $request->featuredesc;
        $quickbyte->tags = $request->Taglist;
        $quickbyte->topics = (count($request->Ltopics) > 0) ? implode(',', $request->Ltopics) : '';
        $quickbyte->sponsored = ($request->is_sponsored) ? '1' : '0';
        $quickbyte->campaign_id = $request->campaign;
        //$quickbyte->add_date = date('Y-m-d H:i:s');
        //$quickbyte->publish_date = date('Y-m-d H:i:s');
        $quickbyte->status = $request->status;
        //$quickbyte->created_at = date('Y-m-d H:i:s');
        $quickbyte->updated_at = date('Y-m-d H:i:s');
        $quickbyte->update();

        $id = $request->id;


        //Quickbyte Category - Save New: Delete Old
        // $arrExistingCats =
        DB::table('quickbyte_category')->where('quickbyte_id', '=', $id)->delete();
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

        $fileTran = new FileTransfer();
        foreach ($images as $image) {
            if (isset($request->photographby[$image])) {

                $source_thumb = $_SERVER['DOCUMENT_ROOT'] . '/files/thumbnail/' . $image;
                $source = '';
                $dest = config('constants.awquickbytesimageextralargedir');
                $fileTran->tranferFile($image, $source, $dest, false);
                $destination = config('constants.awquickbytesimagethumbtdir');
                $fileTran->resizeAndTransferFile($image, '90X63', $source, $destination);
                $destination = config('constants.awquickbytesimagemediumdir');
                $fileTran->resizeAndTransferFile($image, '349X219', $source, $destination);
                if (is_file($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image))
                    unlink($_SERVER['DOCUMENT_ROOT'] . '/files/' . $image);
                if (is_file($source_thumb))
                    unlink($source_thumb);

                $imageEntry = new Photo();
                $imageEntry->title = $request->imagetitle[$image];
                $imageEntry->description = $request->imagedesc[$image];
                $imageEntry->photo_by = $request->photographby[$image];
                $imageEntry->photopath = $image;
                $imageEntry->imagefullPath = '';
                $imageEntry->channel_id = $request->channel;
                $imageEntry->owned_by = 'quickbyte';
                $imageEntry->owner_id = $id;
                $imageEntry->active = '1';
                $imageEntry->created_at = date('Y-m-d H:i:s');
                $imageEntry->updated_at = date('Y-m-d H:i:s');
                $imageEntry->save();
            }
        }
        //If has been Saved by Editor
        if ($request->status == 'P') {
            Session::flash('message', 'Your Quickbte has been Published successfully.');
            return redirect('/quickbyte/list/published?channel=' . $request->channel);
        }

        if ($request->status == 'D') {
            Session::flash('message', 'Your Article has been Saved successfully.');
            return redirect('/quickbyte/list/deleted?channel=' . $request->channel);
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
    public function destroy() {
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

    public function publishBulk() {
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

    public function sortImage($id, Request $request) {

        foreach ($request->row as $k => $itm) {
            $articlePhoto = Photo::find($itm);
            $articlePhoto->sequence = $k + 1;
            $articlePhoto->updated_at = date('Y-m-d H:i:s');
            $articlePhoto->save();
        }

        DB::table('quickbyte')
                ->where('id', $id)
                ->update(['updated_at' => date('Y:m:d H:i:s')]);
    }
    
    function relatedImage(Request $request) {        
        //echo '123'; exit;
        
        /*array:2 [
            "search_key" => "modi"
            "selected_values" => array:3 [
              0 => "tag"
              1 => "qbtitle"
              2 => "imagetitle"
            ]
          ]
          */
        //dd($request->all());
        /*
         select *,count(*) as cs from tags join (select * from article_tags order by updated_at desc ) as article_tags on tags.tags_id=article_tags.tags_id where (tag like '%modi %' or tag like '% modi%' or tag like 'modi' ) group by tags.tags_id order by article_tags.updated_at desc,cs desc limit 5
         * 
         */
        $total = 25;
        echo $query = "select *,count(*) as cs from tags join (select * from article_tags order by updated_at desc ) as article_tags on tags.tags_id=article_tags.tags_id where (tag like '%" . $request->search_key . " %' or tag like '% " . $request->search_key . "%'  or tag like '" . $request->search_key . "' ) group by tags.tags_id order by article_tags.updated_at desc,cs desc limit 5"; exit;
        $tags = DB::select($query);
        $related_images = array();
        $imgids = array();
        $cond = '';
        
        //dd($tags); exit;
        
        usort($tags, array($this, 'compareByCount'));
        if (count($tags) > 0) {
            $minlimit = ceil($total / count($tags));
            $maxlimit = ceil($total / count($tags));
            foreach ($tags as $tag) {
                if (count($imgids) > 0) {
                    $cond = ' and photo_id not in (' . implode(',', $imgids) . ')';
                }
                $imagequery = "select photo_id,photopath,photo_by,title from photos where valid='1' and photopath!='' and owned_by='article' $cond and owner_id in(SELECT articles.article_id FROM `articles` inner join article_tags on articles.article_id=article_tags.article_id WHERE  article_tags.tags_id=" . $tag->tags_id . " order by article_tags.updated_at desc) group by photopath order by updated_at desc limit " . $maxlimit;
                $images = DB::select($imagequery);
                if (count($images) < $maxlimit) {
                    $maxlimit = $maxlimit + ($minlimit - count($images));
                } else {
                    $maxlimit = $minlimit;
                }
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
    
}
