<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use Auth;
use App\Country;
use App\State;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class eventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
            $posts = DB::table('category')
                ->join('users', 'users.id', '=', 'category.user_id')
		->select('category.*','category.category_id','users.id','users.name as userssname'  )
                ->where('category.valid', '=', '1')
                ->where('category.name', 'LIKE', '%'.$queryed.'%')
		->get();
          
        }
        else{
        $posts = DB::table('category')
                ->join('users', 'users.id', '=', 'category.user_id')
		->select('category.*','category.category_id','users.id','users.name as userssname'  )
                ->where('category.valid', '=', '1')   
		->get();
        //print_r($posts);
        } 
        $country = Country::where('valid','=','1')->get();
        $states = State::where('valid','=','1')->orderBy('name')->get();
        return view('events.add-new-events',compact('states','country'));
    }
 

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        // print_r($request);die;
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
        $channel_id = $request->channel;
        $title = $request->title;
        $description = $request->descripation;
        $photo = $imageurl;
        $start_date = date("Y-m-d", strtotime($request->startdate));
        $end_date = date("Y-m-d", strtotime($request->enddate));
        $url = $request->url;
        $country=$request->country;
        $state= $request->state;
        if(!empty($request->category)){
        $sponsored = $request->category;
        }else{
           $sponsored = '0'; 
        }
        $start_time = $request->hours.':'.$request->minutes;
        $end_time = $request->endhours.':'.$request->endminutes;
        $venue=$request->venue;
        $valid = '1';
        $created_at=date('Y-m-d H:i:s');
        $updated_at=date('Y-m-d H:i:s');
       DB::table('event')->insert(
        ['title' => $title, 'description' => $description,'channel_id'=>$channel_id,'imagepath'=>$photo,'start_date'=>$start_date,'end_date'=>$end_date,'start_time'=>$start_time,'end_time'=>$end_time,'country'=>$country,'state'=>$state,'image_url'=>$url,'category'=>$sponsored,'valid'=>$valid,'created_at'=>$created_at,'updated_at'=>$updated_at ]
        );

        Session::flash('message', 'Your data has been successfully add.');
        return Redirect::to('event/add-event-management');
      
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function published()
            
    {
        if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
            $posts = DB::table('event')
               ->join('country_states', 'country_states.state_id', '=', 'event.state')
               ->select('event.*','country_states.name'  )
                ->where('event.valid', '=', '1')
                ->where('event.title', 'LIKE', '%'.$queryed.'%')
		->get();
            //print_r($posts);die;
          
        }elseif(isset($_GET['country'])||isset($_GET['state'])||isset($_GET['startdate'])||isset($_GET['enddate'])) { 
               //echo  $_GET['country'].'sumit'; echo $_GET['state'].'sumit4';  echo $_GET['startdate'].'sumitstart'; echo $_GET['enddate'];
               //die;
                 $q = DB::table('event')
               ->join('country_states', 'country_states.state_id', '=', 'event.state')
               ->select('event.*','country_states.name'  )
                ->where('event.valid', '=', '1');
                 
               
                if($_GET['country']) {
                    
                       $q->where('event.country','=',$_GET['country']);
                  }
                if($_GET['state']) {
                       $q->where('event.state','=',$_GET['state']);
                        
                  }
                if($_GET['startdate']) {
                       $startdate = date("Y-m-d", strtotime($_GET['startdate']));
                       $q->where('event.start_date','=',$startdate);
                      
                  }
                if($_GET['enddate']) {
                       $enddate = date("Y-m-d", strtotime($_GET['enddate']));
                       //echo $enddate;
                       $q->where('event.end_date','=',$enddate);
                       // echo  $_GET['country'].'sumit'; echo $_GET['state'].'sumit4';  echo $_GET['startdate'].'sumitstart'; echo $_GET['enddate'];die;
               
                  }
                 //echo $q->count();
		$posts= $q->get();
                // print_r($posts); die;
                  }else{
        
        
             $posts = DB::table('event')
               ->join('country_states', 'country_states.state_id', '=', 'event.state')
               ->select('event.*','country_states.name'  )
                ->where('event.valid', '=', '1')   
		->get();
        //print_r($posts);die;
                  }
        $country = Country::where('valid','=','1')->get();
        $states = State::where('valid','=','1')->orderBy('name')->get();
        
        return view('events.published-event',compact('states','country','posts'));
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
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        //fwrite($asd, " EDIT ID Passed ::" .$id  . "\n\n");
       $posts = DB::table('event')
               ->join('country_states', 'country_states.state_id', '=', 'event.state')
               ->join('country', 'country.country_id', '=', 'event.country')
               ->select('event.*','country_states.name','country.name as countryname'  )
                ->where('event.valid', '=', '1')
                ->where('event.event_id', '=', $id)
		->get();
        $country = Country::where('valid','=','1')->get();
        $states = State::where('valid','=','1')->orderBy('name')->get();
        return view('events.edit-event',compact('states','country','posts'));
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
            DB::table('event')
            ->where('event_id',$d)
            ->update($deleteAl);
            
        }
        return;
    }
  
    
   
}