<?php

namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class categoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }
    
    public function index()
    {
        
        /* Right mgmt start */
        $rightId = 75;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
        
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
            $posts = DB::table('category')
                ->join('users', 'users.id', '=', 'category.user_id')
		->select('category.*','category.category_id','users.id','users.name as userssname'  )
                ->where('category.valid', '=', '1')
                ->where('category.channel_id',$currentChannelId)
                ->where('category.name', 'LIKE', '%'.$queryed.'%')
		->get();
          
        }
        else{
        $posts = DB::table('category')
                ->join('users', 'users.id', '=', 'category.user_id')
		->select('category.*','category.category_id','users.id','users.name as userssname'  )
                ->where('category.valid', '=', '1')
                ->where('category.channel_id',$currentChannelId)
		->get();
        //print_r($posts);
        } 
        $uid = Session::get('users')->id;
       
        return view('categorymaster.categorymaster',compact('posts','channels','currentChannelId'));
    }
 
public function subcategoryindex()
    {
        
       if(isset($_GET['keyword'])){
           $queryed = $_GET['keyword'];
            $id = $_GET['id'];
            $posts = DB::table('category_two')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_two.user_id')
		->select('category_two.*','category.category_id','users.id','users.name as userssname')
                ->where('category_two.valid', '=', '1')  
                ->where('category_two.category_id', '=', $id )
                ->where('category_two.name', 'LIKE', '%'.$queryed.'%')
		->get();

        }
        else{
         $id = $_GET['id'];   
        $posts = DB::table('category_two')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_two.user_id')
		->select('category_two.*','category.category_id','users.id','users.name as userssname')
                ->where('category_two.valid', '=', '1') 
                ->where('category_two.category_id', '=',$id ) 
		->get();
        //print_r($posts);
        } 
        
        return view('categorymaster.sub_category_master',compact('posts'));
    }
    
    public function subcategorythirdindex()
    {
        
       if(isset($_GET['keyword'])){
           $queryed = $_GET['keyword'];
           $id=$_GET['id']; 
            $posts = DB::table('category_three')
                ->join('category_two', 'category_two.category_two_id', '=', 'category_three.category_two_id')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_three.user_id')
		->select('category_three.*','category_two.category_two_id','category.category_id','users.id','users.name as userssname')
                ->where('category_three.valid', '=', '1')  
                 ->where('category_three.category_two_id', '=', $id)
                ->where('category_three.name', 'LIKE', '%'.$queryed.'%')
		->get();
            //print_r($posts);

        }
        else{
         $id=$_GET['id']; 
         $posts = DB::table('category_three')
                ->join('category_two', 'category_two.category_two_id', '=', 'category_three.category_two_id')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_three.user_id')
		->select('category_three.*','category_two.category_two_id','category.category_id','users.id','users.name as userssname')
                ->where('category_three.valid', '=', '1')
                ->where('category_three.category_two_id', '=', $id)
		->get();
        //print_r($posts);
        } 
        
        return view('categorymaster.sub_category_thirdlable',compact('posts'));
    }
     public function subcategoryfourindex()
    {
        
       if(isset($_GET['keyword'])){
           $queryed = $_GET['keyword'];
            $id=$_GET['id'];
           //echo $queryed ;
            $posts = DB::table('category_four')
                ->join('category_three', 'category_three.category_three_id', '=', 'category_four.category_three_id')     
                ->join('category_two', 'category_two.category_two_id', '=', 'category_three.category_two_id')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_four.user_id')
		->select('category_four.*','category_three.category_three_id','category_two.category_two_id','category.category_id','users.id','users.name as userssname')
                ->where('category_four.valid', '=', '1')  
                ->where('category_four.category_three_id', '=', $id)
                ->where('category_four.name', 'LIKE', '%'.$queryed.'%')
		->get();
            //print_r($posts); exit();
        }
        else{
             $id=$_GET['id'];
         $posts = DB::table('category_four')
                ->join('category_three', 'category_three.category_three_id', '=', 'category_four.category_three_id')     
                ->join('category_two', 'category_two.category_two_id', '=', 'category_three.category_two_id')
                ->join('category', 'category.category_id', '=', 'category_two.category_id')
                ->join('users', 'users.id', '=', 'category_four.user_id')
		->select('category_four.*','category_three.category_three_id','category_two.category_two_id','category.category_id','users.id','users.name as userssname')
                ->where('category_four.valid', '=', '1')
                ->where('category_four.category_three_id', '=', $id)
		->get();
        
        } 
        return view('categorymaster.sub_category_fourlable',compact('posts'));
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($request)
    {
        //Save Request Tuple in Table - Validate First
        // ----- Not Being used for now ----//

       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        
       if($request->pt_id){
        $parantname = $request->pt_name ; 
        
        $category_id = $request->pt_id;
        $name = $request->addsubcategory;
         $id = Auth::id();
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('category_four')->insert(
        ['category_three_id' => $category_id, 'name' => $name,'user_id'=>$id,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );

        Session::flash('message', 'Your data has been successfully add.');
        $url= 'sub-category_third_master/add/?name='.$parantname.'&id='.$category_id;
        return Redirect::to($url);
       }elseif($request->ps_id){
        $parantname = $request->ps_name ; 
        
        $category_id = $request->ps_id;
        $name = $request->addsubcategory;
        $id = Auth::id();
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('category_three')->insert(
        ['category_two_id' => $category_id, 'name' => $name, 'user_id'=>$id,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );

        Session::flash('message', 'Your data has been successfully add.');
        $url= 'sub-category_second_master/add/?name='.$parantname.'&id='.$category_id;
        return Redirect::to($url);
       }elseif($request->p_id){
        $parantname = $request->p_name ; 
        
        $category_id = $request->p_id;
        $name = $request->addsubcategory;
        $id = Auth::id();
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('category_two')->insert(
        ['category_id' => $category_id, 'name' => $name,'user_id'=>$id,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );

        Session::flash('message', 'Your data has been successfully add.');
        $url= 'sub-category-master/add/?name='.$parantname.'&id='.$category_id;
        return Redirect::to($url);
       }else{
        
         /* Right mgmt start */
        $rightId = 75;
        $currentChannelId =$request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */     
           
        $channel_id = $request->channel;
        $name = $request->add_mastercategory;
        $id = Auth::id();
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('category')->insert(
        ['channel_id' => $channel_id, 'name' => $name,'user_id'=>$id,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );

        Session::flash('message', 'Your data has been successfully add.');
        return Redirect::to('category/add-master-category');
       }
       
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
        $editAuthor = Author::where('author_id',$id)
            ->select('authors.*')
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
            DB::table('category')
            ->where('category_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
     public function destroysecond()
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
            DB::table('category_two')
            ->where('category_two_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
  public function destroysthird()
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
            DB::table('category_three')
            ->where('category_three_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
    public function destroysfour()
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
            DB::table('category_four')
            ->where('category_four_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
    
   
}
