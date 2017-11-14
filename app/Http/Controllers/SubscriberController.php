<?php

namespace App\Http\Controllers;

use Redirect;
use Illuminate\Http\Request;
use DB;
use App\Right;
use App\SbuscriptionPackage;
use App\Subscriber;
use App\Channel;
use App\Country;
use App\State;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class SubscriberController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function __construct() {
        $this->middleware('auth');
        $this->rightObj = new Right();
    }

    public function index() {
        /* Right mgmt start */
        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $query = DB::table('subscribers');

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $query->where('subscribers.first_name', 'LIKE', '%' . $queryed . '%');
        }

        $query->where('is_deleted', '=', '0');
        $query->orderby('updated_at', 'desc');
        $subscribers = $query->paginate(config('constants.recordperpage'));

        return view('subscription.subscriber.subscriber', compact('subscribers'));
    }
      public function deleted() {
        /* Right mgmt start */
        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $query = DB::table('subscribers');

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $query->where('subscribers.first_name', 'LIKE', '%' . $queryed . '%');
        }

        $query->where('is_deleted', '=', '1');
        $query->orderby('updated_at', 'desc');
        $subscribers = $query->paginate(config('constants.recordperpage'));

        return view('subscription.subscriber.deleted', compact('subscribers'));
    }

    public function create() {
        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');

        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $channels = Channel::where('valid', '=', '1')->orderBy('channel')->get();
        return view('subscription.subscriber.create', compact('country', 'states', 'channels'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {

        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        $rule=[
                 'first_name'=>'required',
       		 'email' => 'required|email|unique:subscribers,email',
        	 'mobile' => 'required|unique:subscribers,mobile',
                 'sex' => 'required',
                 'dob'=>'date_format:Y-m-d',
                 'country' => 'required',
    	];
        if($request->country == 1)
            $rule['state']='required';
        
        $this->validate($request,$rule);
        

        $subscriber = new Subscriber();
        $subscriber->first_name = $request->first_name;
        $subscriber->last_name = $request->last_name;
        $subscriber->email = $request->email;
        $subscriber->mobile = $request->mobile;
        $subscriber->sex = $request->sex;
        $subscriber->dob = $request->dob;
        $subscriber->address1 = $request->address1;
        $subscriber->address2 = $request->address2;
        $subscriber->city = $request->city;
        $subscriber->country = $request->country;
        $subscriber->zip = $request->pin;
        if ($request->country == 1)
            $subscriber->state = $request->state;
        else
            $subscriber->state = 0;
        $subscriber->save();

        $subscriber_id = $subscriber->id;
        Session::flash('message', 'Subscriber created sucessfully.');
        return Redirect::to('subscribers');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {
        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        //  echo $id; exit; 

        $subscriber = Subscriber::find($id);

        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $channels = Channel::where('valid', '=', '1')->orderBy('channel')->get();
        return view('subscription.subscriber.edit', compact('subscriber', 'country', 'states', 'channels'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {

        $rightId = 89;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $subscriber = Subscriber::find($request->id);

        $rule=[
                 'first_name'=>'required',
       		 'email' => 'required|email|unique:subscribers,email,'.$request->id,
        	 'mobile' => 'required|unique:subscribers,mobile,'.$request->id,
                 'sex' => 'required',
                 'dob'=>'date_format:Y-m-d',
                 'country' => 'required',
    	];
        if($request->country == 1)
            $rule['state']='required';
        $this->validate($request,$rule);
        
        $subscriber->first_name = $request->first_name;
        $subscriber->last_name = $request->last_name;
        $subscriber->email = $request->email;
        $subscriber->mobile = $request->mobile;
        $subscriber->sex = $request->sex;
        $subscriber->dob = $request->dob;
        $subscriber->address1 = $request->address1;
        $subscriber->address2 = $request->address2;
        $subscriber->city = $request->city;
        $subscriber->country = $request->country;
        $subscriber->zip = $request->pin;
        if ($subscriber->country == 1)
            $subscriber->state = $request->state;
        else
            $subscriber->state = 0;
        $subscriber->save();


        Session::flash('message', 'Subscriber updated sucessfully.');
        return Redirect::to('subscribers');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
        
         $rightId = 89;
          if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
          return redirect('/dashboard');
          
          if (isset($_GET['option'])) {
            $id = $_GET['option'];
          }

          $delArr = explode(',', $id);

          foreach ($delArr as $d) {
          $sub=Subscriber::find($d);
          $sub->is_deleted=1;
          $sub->save();
          } 
        return 'success';
    }
     public function activate() {
        
         $rightId = 89;
          if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
          return redirect('/dashboard');
          
          if (isset($_GET['option'])) {
            $id = $_GET['option'];
          }

          $delArr = explode(',', $id);

          foreach ($delArr as $d) {
          $sub=Subscriber::find($d);
          $sub->is_deleted=0;
          $sub->save();
          } 
        return 'success';
    }
    
    

}
