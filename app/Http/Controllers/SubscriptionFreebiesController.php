<?php
namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;
use DB;
use App\Right;
use App\Magazineissue;
use App\Channel;
use App\Topic;
use App\SbuscriptionFreebies;
use App\SubscriptionPackageFreebies;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionFreebiesController extends Controller
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
        $rightId = 93;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
       $query = DB::table('subscription_freebies');
       
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
                $query->where('subscription_freebies.description', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->where('is_deleted','=','0');
        $query->orderby('updated_at','desc');
        $freebies=$query->paginate(config('constants.recordperpage'));
        //print_r($freebies); exit;
        return view('subscription.freebies.freebies',compact('freebies'));
    }
 

    
    public function create()
    {
        $rightId =93;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $magazines=Channel::join('magazine','channels.channel_id','=','magazine.channel_id')
        ->select('channels.channel_id','channels.magazine')->groupBy('channels.channel_id')->get();
        
        return view('subscription.freebies.create',compact('magazines'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       
        $rightId =93;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
            
         $this->validate($request,[
       		 'description' => 'required',
    			]); 
    			     
        $freebies=new SbuscriptionFreebies();
        $freebies->description=$request->description;
        $freebies->status=$request->status;
        $freebies->save();
        
        
        
        Session::flash('message', 'Freebies created sucessfully.');
        return Redirect::to('subscription/freebies');
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $rightId = 93;
        
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
            
        $freebies=SbuscriptionFreebies::find($id);    
        return view('subscription.freebies.edit',compact('freebies'));
        
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
         $rightId = 93;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
         $this->validate($request,[
       		 'description' => 'required',
    			]); 
    			
    			
    	  $freebies=SbuscriptionFreebies::find($request->freebies_id);
        $freebies->description=$request->description;
        $freebies->status=$request->status;
        $freebies->save();
        
        
         Session::flash('message', 'Freebies updated sucessfully.');
         return Redirect::to('subscription/freebies');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
         $rightId = 93;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            
        }
           
        $delArr = explode(',', $id);

        foreach ($delArr as $d) {
             $topic=SbuscriptionFreebies::find($d);
             $topic->is_deleted=1;
             $topic->save();
        }
      return 'success';
    }
    
    
    
   
}
