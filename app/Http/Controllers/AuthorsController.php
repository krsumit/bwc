<?php

namespace App\Http\Controllers;

use App\Author;
use Illuminate\Http\Request;

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
        //
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
        }
        $author->is_columnist = $isCol;
        $author->column_id = $request->name;
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
        //$author->photo = $request->photo;
        $author->twitter = $request->twitter;

        //If columnd_id is not NULL then is_columnist is Set
        $isCol = 0;
        if($request->column_id > 0){
            $isCol = '1';
        }
        $author->is_columnist = $isCol;
        $author->column_id = $request->name;
        $author->valid = '1';

        $author->save();
        $arr=array('status'=>'success');
         }else{
            $arr=array('status'=>'error','msg'=>'Email already registred');
        }
        return $arr;
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
