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
use App\SbuscriptionDiscount;
use App\SubscriptionPackageDetail;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriptionDiscountController extends Controller
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
        $rightId = 88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */ 
        
       $query = DB::table('subscription_discount');
       
       if(isset($_GET['keyword'])){
            $queryed = $_GET['keyword'];
                $query->where('subscription_discount.no_of_magazine', 'LIKE', '%'.$queryed.'%');
	
        }
        
        $query->orderby('updated_at','desc');
        $discounts=$query->paginate(config('constants.recordperpage'));
        
        return view('subscription.discount.discount',compact('discounts'));
    }
 

    
    public function create()
    {
        $rightId =88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
                //        echo 'Coming Soon'; exit;
        $magazines=Channel::join('magazine','channels.channel_id','=','magazine.channel_id')
        ->select('channels.channel_id','channels.magazine')->groupBy('channels.channel_id')->get();
        
        return view('subscription.discount.create',compact('magazines'));
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
       
        $rightId =88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
             $this->validate($request, [
       		 'no_of_magazine' => 'required|integer|unique:subscription_discount,no_of_magazine',
        		 'discount_type' => 'required',
             'discount' => 'required|numeric',
    			]);

        $discount=new SbuscriptionDiscount();
        $discount->no_of_magazine=$request->no_of_magazine;
        $discount->discount_type=$request->discount_type;
        $discount->discount=$request->discount;
        $discount->status=$request->status;
        $discount->save();
        $discount_id=$discount->id;
                
        Session::flash('message', 'Discounts created sucessfully.');
        return Redirect::to('subscription/discounts');
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $rightId = 88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
            
       $discountDetail=SbuscriptionDiscount::find($id);  
       
       return view('subscription.discount.edit',compact('discountDetail'));
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
           $rightId =88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
         $discount=SbuscriptionDiscount::find($request->discount_id);   
          
             $this->validate($request, [
       		 'no_of_magazine' => 'required|integer|unique:subscription_discount,no_of_magazine,'.$discount->id,
        		 'discount_type' => 'required',
             'discount' => 'required|numeric',
    			]);


        $discount->no_of_magazine=$request->no_of_magazine;
        $discount->discount_type=$request->discount_type;
        $discount->discount=$request->discount;
        $discount->status=$request->status;
        $discount->save();

             
        Session::flash('message', 'Discounts updated sucessfully.');
        return Redirect::to('subscription/discounts');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy()
    {
         $rightId = 88;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
            
        }
           
        $delArr = explode(',', $id);

        foreach ($delArr as $d) {
             $discounts=SbuscriptionDiscount::find($d);
             $discounts->delete();
        }
      return 'success';
    }
    
    
    
   
}
