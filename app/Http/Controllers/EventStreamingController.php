<?php
namespace App\Http\Controllers;
use Redirect;
use App\Category;
use Illuminate\Http\Request;
use DB;
use App\Right;
use Auth;
use App\EventStreaming;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\FileTransfer;
use App\Helpers\Helper;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class EventStreamingController extends Controller {

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
        
        $rightId=110;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        $rightId = 103;
        $streamings = EventStreaming::where('channel_id','=',$currentChannelId)->orderBy('created_at', 'desc');
        
        if (isset($_GET['searchin'])) {
            if ($_GET['searchin'] == 'name') {
                $speakers->where('name', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (@$_GET['searchin'] == 'email') {
                $speakers->where('email', 'like', '%' . $_GET['keyword'] . '%');
            }
        }
        $streamings = $streamings->paginate(config('constants.recordperpage'));
        return view('eventsstreaming.index', compact('streamings','channels','currentChannelId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() { 
        $rightId=110;
         //dd(Session::get('user_rights'));
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
         if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        return view('eventsstreaming.add-streaming',compact('channels'));
    }

    public function store(Request $request) {       
        $rightId = 110;
        //dd($request->all());
       
        if (!$this->rightObj->checkRights($request->channel_sel,$rightId))
            return redirect('/dashboard');
        
         $validation = $this->validate($request, [
                'channel_sel' => 'required',
                'event_name' => 'required',
                'banner_image' => 'required|image|mimes:jpeg,png|min:1|max:500',
                'embed_code' => 'required'
            ]);
        $filename = '';
        if ($request->file('banner_image')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('banner_image')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $filename=str_replace(' ', '_', $filename);
            $filename=Helper::cleanFileName($filename);
            $fileTran->uploadFile($request->file('banner_image'), config('constants.eventstreaming'), $filename);
        }
        
        $streaming = new EventStreaming;
        $streaming->channel_id = $request->channel_sel;
        $streaming->event_name = $request->event_name;
        $streaming->banner_image =$filename;
        $streaming->embed_code = $request->embed_code;
        if($request->has('is_live'))
            $streaming->is_live ='1';
        $streaming->created_by = Session::get('users')->id;;
        $streaming->save();        
        Session::flash('message', 'Streaming added successfully.');
        return Redirect::to('event/streaming?channel=' .$request->channel_sel);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
  
    public function edit($id) {    
        $rightId = 110;
        $streaming=EventStreaming::find($id);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($streaming->channel_id,$rightId))
            return redirect('/dashboard');
        return view('eventsstreaming.edit-streaming',compact('channels','streaming'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request,$id) {
         $rightId = 110;
         $streaming=EventStreaming::find($id);
         if (!$this->rightObj->checkRights($streaming->channel_id,$rightId))
            return redirect('/dashboard');
         
        $validation = $this->validate($request, [
                'channel_sel' => 'required',
                'event_name' => 'required',
                'banner_image' => 'image|mimes:jpeg,png|min:1|max:500',
                'embed_code' => 'required'
            ]);
        $filename = '';
        
        if ($request->file('banner_image')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('banner_image')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $filename=str_replace(' ', '_', $filename);
            $filename=Helper::cleanFileName($filename);
            $fileTran->uploadFile($request->file('banner_image'), config('constants.eventstreaming'), $filename);
             if (trim($streaming->banner_image)) {
                    $fileTran->deleteFile(config('constants.eventstreaming'),$streaming->banner_image);
                }
        }
               
        $streaming->channel_id = $request->channel_sel;
        $streaming->event_name = $request->event_name;
        if (!empty($filename))
            $streaming->banner_image =$filename;
        $streaming->embed_code = $request->embed_code;
        if($request->has('is_live'))
            $streaming->is_live ='1';
        else
            $streaming->is_live ='0';
        $streaming->updated_by = Session::get('users')->id;;
        $streaming->save();  
        Session::flash('message', 'Streaming updated successfully.');
        return Redirect::to('event/streaming?channel=' .$request->channel_sel); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteStreaming(Request $request) {
       
        $rightId = 110;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId)) {
            return 'You are not authorized to access.';
        }
        /* Right mgmt end */
        foreach ($request->checkItem as $d) {
            $speaker = EventStreaming::find($d);
            $speaker->status=0;
            $speaker->save();
            $activityLog=new ActivityLog();
            $activityLog->storeActivity('delete','attendee', $speaker);  
            
        }
        Session::flash('message', 'Streaming deleted successfully');
        return Redirect::to('attendee');
        return;
    }

    

}
