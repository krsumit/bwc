<?php
namespace App\Http\Controllers;
use Redirect;
use Illuminate\Http\Request;
use DB;
use App\Right;
use App\Magazineissue;
use App\Channel;
use App\Topic;
use App\Subscriber;
use App\SbuscriptionPackage;
use App\SbuscriptionFreebies;
use App\SbuscriptionPackageFreebies;
use App\SubscriptionPackageDetail;
use App\SubscriptionDetail;
use App\SubscriptionDetailMagazine;
use App\SubscriptionPaymentOption;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class SubscriptionController extends Controller
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
    
    public function index($option)
    {  
        $currenttime=time();
        $currentDate=date('Y-m-d H:i:s',$currenttime).'</br>';
        $nextweekTime=$currenttime+(7*24*60*60);
        $nextweekDate=date('Y-m-d H:i:s',$currenttime); 
        
        switch ($option) {
            case "active":
                $rightId = 90;
                $cond=" end_date > '$currentDate' and payment_status='1'";                
                break;
            case "expiring":
                $rightId = 91;
                $cond=" payment_status='1' and end_date between '$currentDate' and '$nextweekDate'";                
                break;
            case "expired":
                $rightId = 92;
                $cond=" end_date <='$currentDate'  and payment_status='1'";                
                break;
            case "pending":
                $rightId = 94;
                $cond=" payment_status='0'";
                break;            
        }
         // $cond='1=1';              
       $query = DB::table('subscription_detail as sd')
               ->join('subscribers as sb','sd.subscriber_id','=','sb.id')
               ->join('subscription_package as sp','sd.package_id','=','sp.id')
               ->join('subscription_detail_magazine as sdm','sd.id','=','sdm.sub_detail_id')
               ->join('channels as ch','sdm.channel_id','=','ch.channel_id')
               ->whereRaw($cond)
               ->select('sd.end_date','sd.status','sd.subscriber_id','sb.first_name','sb.last_name','sb.email','sb.mobile','sd.id',DB::raw('group_concat(ch.magazine) as magazines'),'sp.name')
               ->groupBy('sd.id');
       
       
       if(isset($_GET['keyword'])){
           // $queryed = $_GET['keyword'];
           //     $query->where('subscription_package.name', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->orderby('sd.updated_at','desc');
        $orders=$query->paginate(config('constants.recordperpage'));
        //print_r($subscriber); exit;
        return view('subscription.subscription.'.$option,compact('subscriber','orders'));
        
    }
    
    function userOrder($id){
       
      $rightId = 93;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
        
       $subscriber =Subscriber::find($id);
       
       
       $query = DB::table('subscription_detail as sd')
               ->join('subscription_package as sp','sd.package_id','=','sp.id')
               ->join('subscription_detail_magazine as sdm','sd.id','=','sdm.sub_detail_id')
               ->join('channels as ch','sdm.channel_id','=','ch.channel_id')
               ->select('sd.end_date','sd.id',DB::raw('group_concat(ch.magazine) as magazines'),'sp.name')
               ->where('subscriber_id','=',$id)
               ->groupBy('sd.id');
       
       if(isset($_GET['keyword'])){
           // $queryed = $_GET['keyword'];
           //     $query->where('subscription_package.name', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->orderby('sd.updated_at','desc');
        $orders=$query->paginate(config('constants.recordperpage'));
        //print_r($subscriber); exit;
        return view('subscription.subscription.subscriberorder',compact('subscriber','orders'));
        
    }
    
    function orderDetail($id){
        
      $rightId = 94;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
       $cond="sb.id=$id";              
       $query = DB::table('subscription_detail as sd')
               ->join('subscribers as sb','sd.subscriber_id','=','sb.id')
               ->join('subscription_package as sp','sd.package_id','=','sp.id')
               ->join('subscription_detail_magazine as sdm','sd.id','=','sdm.sub_detail_id')
               ->join('channels as ch','sdm.channel_id','=','ch.channel_id')
               ->whereRaw($cond)
               ->select('sd.*','sb.first_name','sb.last_name','sb.email','sb.mobile',DB::raw('group_concat(ch.magazine) as magazines'),'sp.name','sp.duration_type','sp.duration')
               ->groupBy('sd.id');
        $query->orderby('sd.updated_at','desc');
        $order=$query->first();
        
        $paymentOptions=SubscriptionPaymentOption::where('status','!=','0')->get();
        
        //echo '<pre>';
        //print_r($order);
        return view('subscription.subscription.orderdetail',compact('order','paymentOptions'));
        
    }
    
    function updateOrder(Request $request){
        
        $rightId = 94;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        //echo $request->subscription_id; exit;
        $subdetail=SubscriptionDetail::find(1);
        //echo '<pre>';
        //print_r($request->all()); exit;
         
        $rule=[
                 'payment_status'=>'required',
                 'status'=>'required',
                 'payment_option'=>'required',
       		 'transaction_id' => 'required',
        	 'transaction_detail' => 'required',
                 'proof' => 'mimes:png,jpeg,jpg,gif,pdf|max:3000',
                 'payment_date'=>'required|date_format:Y-m-d',
                 'start_date' => 'required|date_format:Y-m-d',
                 'end_date' => 'required|date_format:Y-m-d',
    	];
          
        $this->validate($request,$rule);
        
        if ($request->file('proof')) { // echo 'test';exit;
                $file = $request->file('proof');
                $filename = 'receipt_'.str_random(6) . '_' . str_replace(' ','_',$request->file('proof')->getClientOriginalName());
                $name = $request->name;
                $destination_path = 'uploads/';
                $file->move($destination_path, $filename);
                $s3 = AWS::createClient('s3');
                $imageurl = $filename;
                if(trim($subdetail->payment_doc)){
                    $result=$s3->deleteObject(array(
			'Bucket'     => config('constants.awbucket'),
			'Key'    => config('constants.awpayslipdir').$subdetail->payment_doc,
                        ));
                }
                $result=$s3->putObject(array(
                                'ACL'=>'public-read',
                                'Bucket'     => config('constants.awbucket'),
                                'Key'    => config('constants.awpayslipdir').$filename,
                                'SourceFile'   => $destination_path.$filename,
                        ));
                  if($result['@metadata']['statusCode']==200){
                        $subdetail->payment_doc=$filename;
                        unlink($destination_path . $filename);
                  }
            }
           $subdetail->status=$request->status;
           $subdetail->payment_status=$request->payment_status;
           $subdetail->payment_option_id=$request->payment_option;
           $subdetail->transaction_id=$request->transaction_id;
           $subdetail->payment_detail=$request->transaction_detail;
           $subdetail->payment_date=$request->payment_date;
           $subdetail->start_date=$request->start_date;
           $subdetail->end_date=$request->end_date;
           $subdetail->save();
           
           Session::flash('message', 'Subscription has been updated.');
          
           return Redirect::away($request->referrer_url);
          
         
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
