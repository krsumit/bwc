<?php
namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;
use DB;
use App\Right;
use App\Magazineissue;
use App\Channel;
use App\Topic;
use App\SbuscriptionPackage;
use App\SbuscriptionFreebies;
use App\SbuscriptionPackageFreebies;
use App\SubscriptionPackageDetail;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionPackageController extends Controller
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
    {   echo 'test'; 
        /* Right mgmt start */
        $rightId = 87;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
       $query = DB::table('subscription_package');
       
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
                $query->where('subscription_package.name', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->where('is_deleted','=','0');
        $query->orderby('updated_at','desc');
        $packages=$query->paginate(config('constants.recordperpage'));
        
        return view('subscription.package.package',compact('packages'));
    }
 

    
    public function create()
    {
        $rightId =87;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $magazines=Channel::join('magazine','channels.channel_id','=','magazine.channel_id')
        ->select('channels.channel_id','channels.magazine')->groupBy('channels.channel_id')->get();
        
        $freebies=SbuscriptionFreebies::where('status','=','1')->get();
        
        
        return view('subscription.package.create',compact('magazines','freebies'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       
        $rightId =87;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
              
             
        $package=new SbuscriptionPackage();
        $package->name=$request->title;
        $package->duration_type=$request->duration_type;
        $package->duration=$request->duration;
        $package->status=$request->status;
        $package->save();
        $package_id=$package->id;
        
        $freebies=array_filter($request->freebies);
          foreach($freebies as $key=>$value){
         	$subf=new SbuscriptionPackageFreebies();
         	$subf->package_id=$package_id;
         	$subf->freebies_id=$value;
         	$subf->save();
         }
         
        foreach($request->price as $channel=>$value){
        	
        		$packageDetail=new SubscriptionPackageDetail();
        		$packageDetail->package_id=$package_id;
        		$packageDetail->channel_id=$channel;
        		$packageDetail->price=$value;
        		$packageDetail->save();

        }
        
        Session::flash('message', 'Package created sucessfully.');
        return Redirect::to('subscription/packages');
       
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $rightId = 87;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $packageDetail=SbuscriptionPackage::find($id);    

		 $priceRows=SubscriptionPackageDetail::where('package_id','=',$id)->get();
		 $prices=array();
		 
		 $freebies=SbuscriptionFreebies::where('status','=','1')->get();

		 $selectedFreebies=SbuscriptionPackageFreebies::where('package_id','=',$id)->get();
		 $selectedFreebiesId=array();
		 foreach($selectedFreebies as $selectedFreebie){

		 	$selectedFreebiesId[]=$selectedFreebie->freebies_id;
		 }		 
		 //print_r($selectedFreebiesId); exit;
		 
		 foreach($priceRows as $row){
		 	$prices[$row->channel_id]=$row->price;
		 }		   
      
        
        $magazines=Channel::join('magazine','channels.channel_id','=','magazine.channel_id')
        ->select('channels.channel_id','channels.magazine')->groupBy('channels.channel_id')->get();
        
        return view('subscription.package.edit',compact('magazines','prices','packageDetail','freebies','selectedFreebiesId'));
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
         $rightId = 87;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
       
        
        $package =SbuscriptionPackage::find($request->packageId);
        $package->name=$request->title;
        $package->duration_type=$request->duration_type;
        $package->duration=$request->duration;
        $package->status=$request->status;
        $package->save();
        
        SbuscriptionPackageFreebies::where('package_id','=',$request->packageId)->delete();
        $freebies=array_filter($request->freebies);
          foreach($freebies as $key=>$value){
         	$subf=new SbuscriptionPackageFreebies();
         	$subf->package_id=$request->packageId;
         	$subf->freebies_id=$value;
         	$subf->save();
         }
         
          
        
        foreach($request->price as $channel=>$value){
        	
        		$packageDetail=SubscriptionPackageDetail::where('package_id','=',$request->packageId)
        		->where('channel_id','=',$channel)
        		->first();
        		
        		if(!$packageDetail)
        		$packageDetail=new SubscriptionPackageDetail();
        		
        		$packageDetail->package_id=$request->packageId;
        		$packageDetail->channel_id=$channel;
        		$packageDetail->price=$value;
        		$packageDetail->save();

        }
        
        
         Session::flash('message', 'Package updated sucessfully.');
         return Redirect::to('subscription/packages');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
         $rightId = 87;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            
        }
           
        $delArr = explode(',', $id);

        foreach ($delArr as $d) {
             $topic=SbuscriptionPackage::find($d);
             $topic->is_deleted=1;
             $topic->save();
        }
      return 'success';
    }
    
    
    
   
}
