<?php

namespace App\Http\Controllers;
use Redirect;
use App\Author;
use Illuminate\Http\Request;
use DB;

use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthorsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        
        //echo $queryed ;exit;
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
           $posts = DB::table('authors')
		->select('authors.*','authors.name')
                ->where('authors.author_type_id', '=', '4')
                ->where('authors.name', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
            
        }else if (isset($_GET['keywordemail'])){
             $queryed = $_GET['keywordemail'];
                $posts = DB::table('authors')
		->select('authors.*','authors.email')
                ->where('authors.author_type_id', '=', '4') 
                ->where('authors.valid', '=', '1')
                ->where('authors.email', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
        }
        else{
            $posts = DB::table('authors')
		->select('authors.*','authors.author_type_id')
                 ->where('authors.author_type_id', '=', '4')   
                 ->where('authors.valid', '=', '1')
		->paginate(10);
            
        }
        $columns = DB::table('columns')
		->select('columns.*')
		->get();
        
        return view('authors.add-edit-author',compact('posts','columns'));
    }
 /**
     * Show the form for guestauthor 
     *
     * @return show
     */
    
    public function gustauthor()
    {
        
        //echo $queryed ;exit;
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
           $posts = DB::table('authors')
		->select('authors.*','authors.name')
                ->where('authors.author_type_id', '=', '3')  
                ->where('authors.valid', '=', '1')
                ->where('authors.name', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
            
        }else if (isset($_GET['keywordemail'])){
             $queryed = $_GET['keywordemail'];
                $posts = DB::table('authors')
		->select('authors.*','authors.email')
                ->where('authors.author_type_id', '=', '3') 
                ->where('authors.valid', '=', '1')
                ->where('authors.email', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
        }
        else{
            $posts = DB::table('authors')
		->select('authors.*','authors.author_type_id')
                 ->where('authors.author_type_id', '=', '3')
                 ->where('authors.valid', '=', '1')   
		->paginate(10);
            
        }
        
        
        return view('authors.add-edit-guestauthor',compact('posts'));
    }
    
     public function bwreporters()
    {
        
        //echo $queryed ;exit;
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
           $posts = DB::table('authors')
		->select('authors.*','authors.name')
                ->where('authors.author_type_id', '=', '2')
                ->where('authors.valid', '=', '1')
                ->where('authors.name', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
            
        }else if (isset($_GET['keywordemail'])){
             $queryed = $_GET['keywordemail'];
                $posts = DB::table('authors')
		->select('authors.*','authors.email')
                ->where('authors.author_type_id', '=', '2') 
                 ->where('authors.valid', '=', '1')       
                ->where('authors.email', 'LIKE', '%'.$queryed.'%')
		->paginate(10);  
        }
        else{
            $posts = DB::table('authors')
		->select('authors.*','authors.author_type_id')
                 ->where('authors.author_type_id', '=', '2')
                ->where('authors.valid', '=', '1')
                    
		->paginate(10);
            
        }
        
        
        return view('authors.add-edit-bw-reporters',compact('posts'));
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

        $author = new Author;

        $author->name = $request->name;
        $author->author_type_id = $request->author_type;
        $author->bio = $request->bio;
        $author->email = $request->email;
        $author->mobile = $request->mobile;
        $author->photo = $request->photo;
        $author->twitter = $request->twitter;

        //If columnd_id is not NULL then is_columnist is Set
        $isCol = 0;
        if($request->column_id > 0){
            $isCol = '1';
        }else{
          $isCol  = $request->is_columnist;
        }
        $author->is_columnist = $isCol;
        $author->column_id = $request->column_id;
        $author->valid = '1';

        $author->save();

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
        //Save Request Tuple in Table - Validate First
       // print_r($request->all());exit;
        // Validation //
        $validation = Validator::make($request->all(), [
            //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
            //'description' => 'required',
            'photo'     => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
        

        $author = new Author;
        if($request->qid){
            $imageurl='';    
       
        if($request->file('photo')){ // echo 'test';exit;
        $file = $request->file('photo');
        //$is_it = '1';
        //$is_it = is_file($file);
        //$is_it = '1';
        $filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        $name = $request->name;
        //var_dump($file);
        //$l = fopen('/home/sudipta/check.log','a+');
        //fwrite($l,"File :".$filename." Name: ".$name);

        $destination_path = 'uploads/';

        //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        //$filename = "PHOTO";
        $file->move($destination_path, $filename);
        $imageurl=url($destination_path . $filename);
        } 
      //echo 'e'; exit;
        $name = $request->name;
        $author_type_id = $request->author_type;
        $bio = $request->bio;
        $email = $request->email;
        $mobile = $request->mobile;
        if(! empty($imageurl)){
        $photo = $imageurl;
        }else{
            $photo = $request->photoset; 
        }
        //$author->photo = $request->photo;
        $twitter = $request->twitter;

        //If columnd_id is not NULL then is_columnist is Set
        $isCol = 0;
        if($request->column_id > 0){
            $isCol = '1';
        }else{
          $isCol  = $request->is_columnist;
        }
        $is_columnist = $isCol;
        if(!empty($request->column_id)){
        $column_id = $request->column_id;
        }else{
           $column_id='0'; 
        }
        $valid = '1';

         $postdata = [
			'name' => $name,
			'author_type_id' => $author_type_id,
			'bio' => $bio,
			'email' => $email,
			'mobile' => $mobile,
			'photo' => $photo,
			'mobile' => $mobile,
			'twitter' => $twitter,
			'is_columnist' => $is_columnist,
			'column_id' => $column_id,
			'valid' => $valid
			
            ];
        DB::table('authors')
            ->where('author_id',$request->qid)
            ->update($postdata);
            if($request->isertedbybwreportersdata=='isertedbybwreportersdata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('bwreporters/add-edit-bw-reporters');
            }
            else if($request->isertedbyguestauthordata=='isertedbyguestauthordata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('guestauthor/add-edit-gustauthor');
            }
            else if($request->isertedbyauthordata=='isertedbyauthordata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('article/add-edit-author');
            }
        }else {
            
       

        if($author->where('email',trim($request->email))->count()==0){
            
       
        //$image = new Image;

        // upload the image //

        //$f = sizeof($_FILES);
        $imageurl='';    
       
        if($request->file('photo')){ // echo 'test';exit;
        $file = $request->file('photo');
        //$is_it = '1';
        //$is_it = is_file($file);
        //$is_it = '1';
        $filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        $name = $request->name;
        //var_dump($file);
        //$l = fopen('/home/sudipta/check.log','a+');
        //fwrite($l,"File :".$filename." Name: ".$name);

        $destination_path = 'uploads/';

        //$filename = str_random(6).'_'.$request->file('photo')->getClientOriginalName();
        //$filename = "PHOTO";
        $file->move($destination_path, $filename);
        $imageurl=url($destination_path . $filename);
        } 
      //echo 'e'; exit;
        $author->name = $request->name;
        $author->author_type_id = $request->author_type;
        $author->bio = $request->bio;
        $author->email = $request->email;
        $author->mobile = $request->mobile;
        $author->photo = $imageurl;
        if(! empty($imageurl)){
        $author->photo = $imageurl;
        }else{
            $author->photo = $request->photoset; 
        }
        //$author->photo = $request->photo;
        $author->twitter = $request->twitter;

        //If columnd_id is not NULL then is_columnist is Set
        $isCol = 0;
        if($request->column_id > 0){
            $isCol = '1';
        }
        else{
          $isCol  = $request->is_columnist;
        }
        $author->is_columnist = $isCol;
         if(!empty($request->column_id)){
        $author->column_id = $request->column_id;
         }
        $author->valid = '1';

        $author->save();
        
        if($request->isertedbybwreportersdata=='isertedbybwreportersdata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('bwreporters/add-edit-bw-reporters');
            }
            else if($request->isertedbyguestauthordata=='isertedbyguestauthordata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('guestauthor/add-edit-gustauthor');
            }
            else if($request->isertedbyauthordata=='isertedbyauthordata'){
                Session::flash('message', 'Your data has been successfully modify.');
                return Redirect::to('article/add-edit-author');
            }
        $arr=array('status'=>'success');
         }else{
            if($request->isertedbybwreportersdata=='isertedbybwreportersdata'):
            Session::flash('message', 'Email already registred.');
            return Redirect::to('bwreporters/add-edit-bw-reporters');
        endif;
        if($request->isertedbyguestauthordata=='isertedbyguestauthordata'):
            Session::flash('message', 'Email already registred.');
            return Redirect::to('guestauthor/add-edit-gustauthor');
        endif;
            if($request->isertedbyauthordata=='isertedbyauthordata'):
            Session::flash('allready', 'Email already registred.');
            return Redirect::to('article/add-edit-author');
        endif;
            $arr=array('status'=>'error','msg'=>'Email already registred');
        }
       
        return $arr;
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
            DB::table('authors')
            ->where('author_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
   
}