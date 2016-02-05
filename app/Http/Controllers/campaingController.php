<?php
namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class campaingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;
    
    public function __construct() {
        $this->rightObj = new Right();
    }
    
    public function index()
    {
        
        /* Right mgmt start */
        $rightId = 30;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        
        
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
            $posts = DB::table('campaign')
		->select('campaign.*')
                ->where('channel_id','=',$currentChannelId)
                ->where('campaign.valid', '=', '1')
                ->where('campaign.title', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
            
        }else {
         $posts = DB::table('campaign')
		->select('campaign.*')
                ->where('channel_id','=',$currentChannelId)
                ->where('campaign.valid', '=', '1')
                    
		->paginate(10);
        }   
        $uid = Session::get('users')->id;
        
        return view('articles.campaing-managment',compact('posts','channels','currentChannelId'));
    }
 

  
    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
       $validation = Validator::make($request->all(), [
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            //'description' => 'required',
            'photo'     => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
       if($request->file('photo')){ // echo 'test';exit;
        $file = $request->file('photo');
       // echo $file; exit;
        //$is_it = '1';
        //$is_it = is_file($file);
        //$is_it = '1';
        $filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        //$name = $request->title;
        //var_dump($file);
        //$l = fopen('/home/sudipta/check.log','a+');
        //fwrite($l,"File :".$filename." Name: ".$name);

        $destination_path = 'uploads/';

        //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        //$filename = "PHOTO";
        $file->move($destination_path, $filename);
        $imageurl=url($destination_path . $filename);
        } 
        if($request->cid){
            $channel_id = $request->channel;
            $title = $request->title;
            $description = $request->description;
            if(! empty($imageurl)){
               $imageurled  = $imageurl;
               }else{
                  $imageurled  = $request->p_photo; 
               }
           
            $valid = '1';
            $postdata = [
			'channel_id' => $channel_id ,
			'title' => $title,
			'description' => $description,
			
			'url' => $imageurled,
			
			'valid' => $valid
			
            ];
        DB::table('campaign')
            ->where('campaign_id',$request->cid)
            ->update($postdata);
             Session::flash('message', 'Your data has been successfully modiffy.');
        }else{
        
        $channel_id = $request->channel;
        $title = $request->title;
        $description = $request->description;
        if(! empty($imageurl)){
               $imageurled  = $imageurl;
               }else{
                  $imageurled  = $request->p_photo; 
               }
           
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('campaign')->insert(
        ['title' => $title, 'description' => $description,'channel_id'=>$channel_id,'url'=>$imageurled,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );
       
        Session::flash('message', 'Your data has been successfully add.');
        }
        return Redirect::to('campaing/add-management?channel='.$request->channel);
       
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
        $editAuthor = DB::table('campaign')
		->select('campaign.*')
               
                ->where('campaign.campaign_id', '=', $id)
        
            ->get();

        
        echo json_encode(array($editAuthor));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
   
    public function destroy()
    {
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            
        }
           // echo $id; die;
        //fwrite($asd, " Del Ids: ".$id." \n\n");
        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $valid='0';
            $deleteAl= [
			
			'valid' => $valid
			
            ];
            DB::table('campaign')
            ->where('campaign_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
    
    
   
}
