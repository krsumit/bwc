<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\Country;
use App\State;
use App\Event;
use App\SpeakerTag;
use App\Speaker;
use App\SpeakerDetails;
use App\ActivityLog;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class EventSpeakerController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        $this->rightObj = new Right();
    }

    public function index() {
        //$event = Event::find($id);
        //DB::enableQueryLog();
        $speakers = Speaker::where('status','=','1')
                ->leftJoin('speaker_details','speakers.id','=','speaker_details.speaker_id')
                ->select('speakers.id','speakers.name','speakers.email','speakers.mobile','speakers.photo','speaker_details.designation','speaker_details.company','speaker_details.city')
                ->groupBy('speakers.id')
                ->orderBy('speakers.updated_at', 'desc');
        $rightId = 103;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        if (isset($_GET['keyword'])) {
           if (trim($_GET['keyword'])){ 
                $keyword=$_GET['keyword'];
                $speakers->where(function ($subquery) use ($keyword){
                    $subquery->where('email', 'like', '%' . $keyword . '%')
                    ->orWhere('mobile', 'like', '%' . $keyword . '%')        
                    ->orWhere('name', 'like', '%' . $keyword . '%');
                });
           }
        }
        
        if (isset($_GET['designation'])) {
             if (trim($_GET['designation']))
               $speakers->where('speaker_details.designation', 'like', '%' . $_GET['designation'] . '%');
        }
        
        if (isset($_GET['company'])) {
             if (trim($_GET['company']))
                $speakers->where('speaker_details.company', 'like', '%' . $_GET['company'] . '%');
        }
        
        if (isset($_GET['location'])) {
            if (trim($_GET['location']))
                $speakers->where('speaker_details.city', 'like', '%' . $_GET['location'] . '%');
        }
        
        $speakers->where(function ($subquery){
                    $subquery->whereNull('is_current')->orWhere('is_current','=','1');        
                });
            
        $speakers = $speakers->paginate(config('constants.recordperpage'));
                
        //$query =DB::getQueryLog();
        
        //dump(end($query));
        
        return view('events.speaker', compact('speakers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() { 
        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        return view('events.add-speaker');
    }

    public function store(Request $request) {
       
         $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        if ($request->has('add-professional-detail')) {

            $validation = $this->validate($request, [
                'speaker_name' => 'required',
                'speaker_mobile' => 'required|digits_between:7,14|unique:speakers,mobile',
                'photo' => 'image|mimes:jpeg,png|min:1|max:250',
                'speaker_designation' => 'required'
            ]);
        } else {

            $validation = $this->validate($request, [
                'speaker_name' => 'required',
                'speaker_mobile' => 'required|digits_between:7,14|unique:speakers,mobile',
                'photo' => 'image|mimes:jpeg,png|min:1|max:250',
            ]);
        }
        
        
        $filename = '';
        if ($request->file('speaker_image')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('speaker_image')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $filename=str_replace(' ', '_', $filename);
            $fileTran->uploadFile($request->file('speaker_image'), config('constants.awspeakerdir'), $filename);
        }

        $speaker = new Speaker;
        $speaker->name = $request->speaker_name;
        $speaker->email = $request->speaker_email;
        $speaker->mobile = $request->speaker_mobile;
        $speaker->photo = $filename;
        $speaker->twitter = $request->speaker_twitter;
        $speaker->linkedin = $request->speaker_linkedin;
        $speaker->description = $request->speaker_desc;
        $speaker->tags = $request->Taglist;
        $speaker->status = '1';
        $speaker->save();
        //Storing activity log
        $activityLog=new ActivityLog();
        $activityLog->storeActivity('create','attendee',$speaker); 
        $speaker_id = $speaker->id;
        if ($speaker_id) {
            if (trim($request->speaker_designation)) {
                $speakerDetail = new SpeakerDetails;
                $speakerDetail->speaker_id = $speaker_id;
                $speakerDetail->designation = trim($request->speaker_designation);
                $speakerDetail->company = trim($request->speaker_company);
                $speakerDetail->mobiles = trim($request->speaker_phone);
                $speakerDetail->emails = trim($request->speaker_emails);
                $speakerDetail->city = trim($request->speaker_city);
                $speakerDetail->address = trim($request->speaker_add);
                $speakerDetail->is_current = '1';
                $speakerDetail->save();
                //Storing activity log
                $activityLog=new ActivityLog();
                $activityLog->storeActivity('create','attendee_profile',$speakerDetail,$speaker);
            }
        }

        Session::flash('message', 'Speaker added successfully.');
        return Redirect::to('attendee');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $speaker=  Speaker::find($id);
        //echo $speaker->speaker_id; exit;
        $speakerDetails =  SpeakerDetails::where('speaker_id','=',$speaker->id)->orderBy('is_current','DESC')->orderBy('created_at','DESC')->get();
        //dd($speakerDetails);
        return view('events.info-speaker',compact('speaker','speakerDetails'));
    }
  
  
    public function edit($id){       
        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        $speaker= Speaker::find($id);
        $tags = new SpeakerTag;
        $tags = json_encode($tags->whereIn('tags_id',explode(',',$speaker->tags))->select('tags_id as id', 'tag as name')->get());
        $profileId=0;
        if(isset($_GET['profile'])){
            $profileId=$_GET['profile'];
        }else{
            $profileDetail=SpeakerDetails::where('speaker_id','=',$speaker->id)->where('is_current','=','1')->first();
            if($profileDetail){
                $profileId=$profileDetail->id;
            }
        }
        $speakerDetails=  SpeakerDetails::where('speaker_id','=',$speaker->id)->orderBy('is_current','desc')->get();  //->get();
        //echo json_encode($speakerDetails); exit;
        return view('events.edit-speaker',compact('speaker','speakerDetails','profileId','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
         $rightId = 104;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');

            $validation = $this->validate($request, [
                'speaker_name' => 'required',
                'speaker_mobile' => 'required|digits_between:7,14|unique:speakers,mobile,'.$id,
                'photo' => 'image|mimes:jpeg,png|min:1|max:250',
                'speaker_designation' => 'required'
            ]);
       
        $filename = '';
        $speaker = Speaker::find($id);
        
        //echo '<pre>';
        //print_r($speaker->all());
        if ($request->file('speaker_image')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('speaker_image')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $filename=str_replace(' ', '_', $filename);
            $fileTran->uploadFile($request->file('speaker_image'), config('constants.awspeakerdir'), $filename);
            if (trim($speaker->photo)) {
                    $fileTran->deleteFile(config('constants.awspeakerdir'),$speaker->photo);
                }
        }

        $speaker->name = $request->speaker_name;
        $speaker->email = $request->speaker_email;
        $speaker->mobile = $request->speaker_mobile;
        if (!empty($filename))
            $speaker->photo = $filename;
        $speaker->twitter = $request->speaker_twitter;
        $speaker->linkedin = $request->speaker_linkedin;
        $speaker->description = $request->speaker_desc;
        $speaker->tags = $request->Taglist;
        $speaker->status = '1';        
        $activityLog=new ActivityLog();
        $activityLog->storeActivity('update','attendee', $speaker); //exit;
        $speaker->save();
        
        //echo $changeStatus; 
        if (trim($request->speaker_designation)) {
            if($request->select_profile==0)
                $speakerDetail = new SpeakerDetails;
            else
                $speakerDetail = SpeakerDetails::find($request->select_profile);
            //dd($speakerDetail);
            $speakerDetail->speaker_id = $id;
            $speakerDetail->designation = trim($request->speaker_designation);
            $speakerDetail->company = trim($request->speaker_company);
            $speakerDetail->mobiles = trim($request->speaker_phone);
            $speakerDetail->emails = trim($request->speaker_emails);
            $speakerDetail->city = trim($request->speaker_city);
            $speakerDetail->address = trim($request->speaker_add);
            $speakerDetail->is_current = trim($request->is_current);
            $activityLog=new ActivityLog();
            if($request->select_profile==0){ 
                $speakerDetail->save();
                $activityLog->storeActivity('create','attendee_profile',$speakerDetail,$speaker);
            }
            else{
                $activityLog->storeActivity('update','attendee_profile',$speakerDetail,$speaker);
                $speakerDetail->save();
            }
            if($request->has('is_current')){
                    //dd($request->all());
                    $update=SpeakerDetails::where('speaker_id', '=',$id)->where('id','!=',$speakerDetail->id)->update(['is_current'=>0]);
                }
            
        }
        Session::flash('message', 'Speaker updated successfully.');
        return Redirect::to('attendee'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteAttendee(Request $request) {
        //dd($request->all());
        
        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId)) {
            return 'You are not authorized to access.';
        }
        /* Right mgmt end */
        foreach ($request->checkItem as $d) {
            $speaker = Speaker::find($d);
            $speaker->status=0;
            $speaker->save();
            $activityLog=new ActivityLog();
            $activityLog->storeActivity('delete','attendee', $speaker);  
            
        }
        Session::flash('message', 'Speaker deleted successfully');
        return Redirect::to('attendee');
        return;
    }

   public function returnSpeakerJson(){
        //DB::enableQueryLog();
        $matchText = $_GET['q'];
        
        $rst = DB::table('speakers')
                ->join('speaker_details', 'speaker_details.speaker_id', '=', 'speakers.id')
                ->where('speakers.name', "like", $matchText . '%')
                ->where('speakers.status', "=", '1')
                ->select(DB::raw("speakers.id,concat(speakers.name,'(',speaker_details.designation,',',speaker_details.company,')') as name"))
                ->where('speaker_details.is_current', '=', '1')
                ->get();
        
        
        //$rst = Speaker::where('name', "like", $matchText . '%')->select(DB::raw('id,name'))->get();
        return response()->json($rst);
    }
    

}
