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
use App\EventSpeaker;
use App\SpeakerTag;
use App\Speaker;
use App\SpeakerDetails;
use App\SpeakerType;
use App\ActivityLog;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classes\FileTransfer;
use Aws\Laravel\AwsFacade as AWS;
use Aws\Laravel\AwsServiceProvider;

class EventController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    private $rightObj;

    public function __construct() {
        //$this->middleware('auth');
        $this->rightObj = new Right();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function index() {

        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 48;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */
        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $posts = DB::table('event')
                    ->leftJoin('country_states', 'country_states.state_id', '=', 'event.state')
                    ->select('event.*', 'country_states.name')
                    ->where('event.valid', '=', '1')
                    ->where('channel_id', $currentChannelId)
                    ->where('event.title', 'LIKE', '%' . $queryed . '%')
                    ->orderBy('event.start_date', 'DESC')
                    ->paginate(10);
            //print_r($posts);die;
        } elseif (isset($_GET['country']) || isset($_GET['state']) || isset($_GET['startdate']) || isset($_GET['enddate'])) {
            //echo  $_GET['country'].'sumit'; echo $_GET['state'].'sumit4';  echo $_GET['startdate'].'sumitstart'; echo $_GET['enddate'];
            //die;
            $q = DB::table('event')
                    ->leftJoin('country_states', 'country_states.state_id', '=', 'event.state')
                    ->select('event.*', 'country_states.name')
                    ->where('channel_id', $currentChannelId)
                    ->where('event.valid', '=', '1')
                    ->orderBy('event.start_date', 'DESC');

            if ($_GET['country']) {

                $q->where('event.country', '=', $_GET['country']);
            }
            if ($_GET['state']) {
                $q->where('event.state', '=', $_GET['state']);
            }
            if ($_GET['startdate']) {
                $startdate = date("Y-m-d", strtotime($_GET['startdate']));
                $q->where('event.start_date', '=', $startdate);
            }
            if ($_GET['enddate']) {
                $enddate = date("Y-m-d", strtotime($_GET['enddate']));
                //echo $enddate;
                $q->where('event.end_date', '=', $enddate);
                // echo  $_GET['country'].'sumit'; echo $_GET['state'].'sumit4';  echo $_GET['startdate'].'sumitstart'; echo $_GET['enddate'];die;
            }
            //echo $q->count();
            $posts = $q->paginate(10);
            // print_r($posts); die;
        } else {


            $posts = DB::table('event')
                    ->leftJoin('country_states', 'country_states.state_id', '=', 'event.state')
                    ->select('event.*', 'country_states.name')
                    ->where('event.valid', '=', '1')
                    ->where('channel_id', $currentChannelId)
                    ->orderBy('event.start_date', 'DESC')
                    ->paginate(10);

            //print_r($posts);die;
        }
        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();

        return view('events.published-event', compact('states', 'country', 'posts', 'channels', 'currentChannelId'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function create() {   
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        $rightId = 50;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $uid = Session::get('users')->id;
        return view('events.add-new-events', compact('states', 'country', 'channels', 'currentChannelId'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 50;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        
        // print_r($request);die;
        $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
        $imageurl = '';
        
        if ($request->file('photo')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $imageurl = $filename;
            $fileTran->uploadFile($request->file('photo'), config('constants.awaevent'), $filename);
        }
        $channel_id = $request->channel;
        $title = $request->title;
        $description = $request->descripation;
        if (!empty($imageurl)) {
            $photo = $imageurl;
        } else {
            $photo = '';
        }
        $start_date = date("Y-m-d", strtotime($request->startdate));
        $end_date = date("Y-m-d", strtotime($request->enddate));
        $url = $request->url;
        $country = $request->country;
        $state = $request->state;

        $sponsored = $request->category;

        //$start_time = $request->hours.':'.$request->minutes;
        //$end_time = $request->endhours.':'.$request->endminutes;
        $venue = $request->venue;
        $valid = '1';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        DB::table('event')->insert(
                ['title' => $title, 'description' => $description, 'channel_id' => $channel_id, 'imagepath' => $photo, 'start_date' => $start_date, 'end_date' => $end_date, 'country' => $country, 'state' => $state, 'image_url' => $url, 'category' => $sponsored, 'valid' => $valid, 'created_at' => $created_at, 'updated_at' => $updated_at]
        );

        Session::flash('message', 'Your data has been successfully add.');
        return Redirect::to('event/published?channel=' . $currentChannelId);
    }

    
    public function edit() {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        }
        $posts = DB::table('event')
                ->leftJoin('country_states', 'country_states.state_id', '=', 'event.state')
                ->leftJoin('country', 'country.country_id', '=', 'event.country')
                ->select('event.*', 'country_states.name', 'country.name as countryname')
                ->where('event.valid', '=', '1')
                ->where('event.event_id', '=', $id)
                ->get();
        /* Right mgmt start */
        $rightId = 50;
        $currentChannelId = $posts[0]->channel_id;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        $country = Country::where('valid', '=', '1')->get();
        $states = State::where('valid', '=', '1')->orderBy('name')->get();
        $uid = Session::get('users')->id;
        return view('events.edit-event', compact('states', 'country', 'posts', 'channels', 'currentChannelId'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 50;
        $currentChannelId = $request->channel;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');
        /* Right mgmt end */

        $validation = Validator::make($request->all(), [
                    //'caption'     => 'required|regex:/^[A-Za-z ]+$/',
                    //'description' => 'required',
                    'photo' => 'image|mimes:jpeg,png|min:1|max:250'
        ]);
        $eventdetais = DB::table('event')
                ->select('event.imagepath')
                ->where('event.event_id', '=', $request->editevent_id)
                ->first();
        if ($request->file('photo')) { // echo 'test';exit;
            $file = $request->file('photo');
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            $destination_path = 'uploads/';
            $fileTran = new FileTransfer();
            $imageurl = $filename;
            $fileTran->uploadFile($request->file('photo'), config('constants.awaevent'), $filename);
            if (trim($eventdetais->imagepath)) {
                $fileTran->deleteFile(config('constants.awaevent'), $eventdetais->imagepath);
            }
        }
        $channel_id = $request->channel;
        $title = $request->title;
        $description = $request->descripation;
        if (!empty($imageurl)) {
            $photo = $imageurl;
        } else {
            $photo = $request->p_photo;
        }
        $start_date = date("Y-m-d", strtotime($request->startdate));
        $end_date = date("Y-m-d", strtotime($request->enddate));
        $url = $request->url;
        $country = $request->country;
        $state = $request->state;

        $sponsored = $request->category;

        $venue = $request->venue;
        $valid = '1';
        $created_at = date('Y-m-d H:i:s');
        $updated_at = date('Y-m-d H:i:s');
        $postdata = ['title' => $title, 'description' => $description, 'channel_id' => $channel_id, 'imagepath' => $photo, 'start_date' => $start_date, 'end_date' => $end_date, 'country' => $country, 'state' => $state, 'image_url' => $url, 'category' => $sponsored, 'valid' => $valid, 'created_at' => $created_at, 'updated_at' => $updated_at];
        DB::table('event')
                ->where('event_id', $request->editevent_id)
                ->update($postdata);
        $url = '/event/edit/?id=' . $request->editevent_id;

        Session::flash('message', 'Your data has been successfully modiffy.');
        return Redirect::to('event/published?channel=' . $currentChannelId);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy() {
        if (isset($_GET['option'])) {
            $id = $_GET['option'];
        }


        /* Right mgmt start */
        $rightId = 50;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId)) {
            return 'You are not authorized to access.';
        }
        /* Right mgmt end */

        $delArr = explode(',', $id);
        //fwrite($asd, " Del Arr Count: ".count($delArr)." \n\n");
        foreach ($delArr as $d) {
            //fwrite($asd, " Delete Id : ".$d." \n\n");
            $valid = '0';
            $deleteAl = [
                'valid' => $valid,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            DB::table('event')
                    ->where('event_id', $d)
                    ->update($deleteAl);
        }
        return;
    }

    public function manageEventSpeaker($id) {
        $event = Event::find($id);
        $speaker_type_id = 1;
        if (isset($_GET['type'])) {
            $speaker_type_id = $_GET['type'];
        }
         /* Right mgmt start */
        $rightId = 105;
        $currentChannelId = $event->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        /* Right mgmt end */
        $speaker_type = SpeakerType::find($speaker_type_id);
        $speaker_types = SpeakerType::get();
        //DB::enableQueryLog(); 
        $speakers = DB::table('event_speaker')
                ->join('speakers', 'event_speaker.speaker_id', '=', 'speakers.id')
                ->join('speaker_details', 'speaker_details.speaker_id', '=', 'speakers.id')
                ->select('speakers.name', 'speakers.mobile', 'speakers.email','speakers.photo','speaker_details.designation','speaker_details.company','event_speaker.id','event_speaker.speaker_id')
                ->where('event_speaker.event_id', '=', $id)
                ->where('event_speaker.speaker_type_id', '=', $speaker_type_id)
                ->where('speaker_details.is_current', '=', '1')
                ->paginate(config('constants.recordperpage'));
        //dd(DB::getQueryLog());
        //dd($speakers);exit;
        return view('events.event-speaker', compact('event', 'speakers', 'speaker_type', 'speaker_types'));
    }

    public function storeEventSpeaker(Request $request, $id) {
        //echo $id;
        //dd($request->all()); 
        $event = Event::find($id);
        $rightId = 106;
        $currentChannelId = $event->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        
        
        $currentChannelId = $event->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        
        $filename = '';
        $uid = Session::get('users')->id;
        if ($request->has('add-attendee')) {
            //   echo 'here 2'; exit;
            $speakers = explode(',', $request->Taglist);
            $skipArray = array();
            foreach ($speakers as $sp_id) {
                $speakerDeailId = SpeakerDetails::where('speaker_id', '=', $sp_id)->where('is_current', '=', '1')->select('id')->first()->id;
                $chkSpeaker = EventSpeaker::where('event_id', '=', $request->event_id)->where('speaker_id', '=', $sp_id)->where('speaker_type_id','=',$request->speaker_type)->first();
                
                if ($chkSpeaker) {
                        if ($chkSpeaker->speaker_detail_id != $speakerDeailId) {
                            $speaker = EventSpeaker::find($chkSpeaker->id);
                            $speaker->speaker_detail_id = $speakerDeailId;
                            $speaker->save();
                        }
                        else {
                           $skipArray[] = array('speaker_id' => $sp_id, 'speaker_type' => $chkSpeaker->speaker_type_id);
                       }
                } else {
                    $speaker = new EventSpeaker();
                    $speaker->event_id = $request->event_id;
                    $speaker->speaker_type_id = $request->speaker_type;
                    $speaker->speaker_id = $sp_id;
                    $speaker->speaker_detail_id = $speakerDeailId;
                    $speaker->assigned_by = $uid;
                    $speaker->save();
                }
            }
            Session::flash('message', 'Speaker added successfully');
        } elseif ($request->has('remove-attendee')) {
            //echo 'here'; exit;
            //dd($request->all());
            //DB::enableQueryLog();
            $deleted_row = EventSpeaker::whereIn('id', $request->checkItem)->delete();
            //dd(DB::getQueryLog());
            Session::flash('message', 'Speaker removed successfully');
        }

        return Redirect::to('event/manage-speaker/' . $request->event_id . '?type=' . $request->speaker_type);
    }

 
    public function apiEventSpeaker($id) {
        $id = base64_decode($id);
        $event = Event::leftJoin('country', 'event.country', '=', 'country.country_id')
                ->leftJoin('country_states', 'event.state', '=', 'country_states.state_id')
                ->where('event.event_id', '=', $id)
                ->select('event_id', 'title', 'description', 'imagepath', 'start_date', 'end_date', 'country.name as country', 'country_states.name as state')
                ->first();

        $event->imagepath = config('constants.awsbaseurl') . config('constants.awaevent') . $event->imagepath;

        $evern_attendee = EventSpeaker::join('speakers', 'event_speaker.speaker_id', '=', 'speakers.id')
                ->join('speaker_type', 'event_speaker.speaker_type_id', '=', 'speaker_type.id')
                ->leftJoin('speaker_details', 'event_speaker.speaker_detail_id', '=', 'speaker_details.id')
                ->leftJoin('speaker_tags', 'speakers.tags', '=', 'speaker_tags.tags_id')
                ->select('speakers.name', 'email', 'mobile', 'tag', 'photo', 'twitter', 'linkedin', 'description', 'designation', 'company', 'mobile', 'emails', 'city', 'address', 'speaker_type.name as type')
                ->orderBy('speaker_type.name')
                ->get();
        $data['event'] = $event;
        $data['attendee'] = $evern_attendee;
        return json_encode($data);
    }

    public function storeTag(Request $request) {

        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId)) {
            return 'You are not authorized to access.';
        }
        $count = 0;
        $tagString = $request->tag;
        $arrTags = explode(',', $tagString);
        $count = sizeof($arrTags);

        //For Response
        $returnTag1 = new SpeakerTag();
        $returnArr = array(); //new Tag();
        $returnTag = new SpeakerTag();
        //For more than 1 Tag Added (Comma separated list)
        if ($count >= 1) {
            if ($count > 1) {
                for ($i = 0; $i < $count; $i++) {
                    $tag = new SpeakerTag;
                    $tag->tag = trim($arrTags[$i]);
                    if ($tagrow = $returnTag->where('tag', trim($arrTags[$i]))->select('tags_id as id', 'tag as name')->first()) {
                        $returnArr[] = $tagrow;
                    } else {
                        if (trim($arrTags[$i])) {
                            $tag->save();
                            $returnArr[] = array('id' => $tag->tags_id, 'name' => $tag->tag);
                        }
                    }
                }
                $returnTag1 = $returnArr;
            } else if ($count == 1) {
                // $l = fopen('/home/sudipta/check.log', 'a+');
                $tag = new SpeakerTag;
                $tag->tag = trim($tagString);
                if ($tagrow = $returnTag->where('tag', trim($tagString))->select('tags_id as id', 'tag as name')->first()) {
                    $returnArr[] = $tagrow;
                } else {
                    $tag->save();
                    $returnArr[] = array('id' => $tag->tags_id, 'name' => $tag->tag);
                }
                // if($returnTag->where('tag',trim($tagString))->count() == 0)
                // $returnTag1 = $returnTag->all()->where('tags_id',"$cond");
            }
        }
        return $returnArr;
    }

    public function returnJson() {
        $rightId = 104;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId)) {
            return 'You are not authorized to access.';
        }
        $matchText = $_GET['q'];
        $tag = new SpeakerTag;
        //->all()
        $rst = $tag->where('tag', "like", $matchText . '%')->select('tags_id as id', 'tag as name')->get();
        return response()->json($rst);
    }

    public function import($id) {
        //echo $id; exit;
        $event = Event::find($id);
        $rightId = 107;
        $currentChannelId = $event->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        $speaker_type_id = 1;
        if (isset($_GET['type'])) {
            $speaker_type_id = $_GET['type'];
        }
        $speaker_type = SpeakerType::find($speaker_type_id);
        $speaker_types = SpeakerType::get();
        return view('events.import-speaker', compact('event', 'speaker_type', 'speaker_types'));
    }

    public function saveImport(Request $request) {
        $event = Event::find($request->event_id);
        $rightId = 107;
        $currentChannelId = $event->channel_id;
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect()->intended('/dashboard');
        
        $fileTran = new FileTransfer();
        //dd($request->all());
        if ($request->file('speaker_file')) {
            $file = $request->file('speaker_file');
            $filename = str_random(6) . '_' . preg_replace('/([^a-zA-Z0-9_.])+/', '-', $request->file('speaker_file')->getClientOriginalName());
            $fileTran->uploadFile($file, config('constants.aw_csv'), $filename, false, false);
        }
        $mobileExp = '/^([0-9]{7,14})$/';
        $uid = Session::get('users')->id;
        $eventId = $request->event_id;
        $noOfEscapped = 0;
        $filePath = $_SERVER['DOCUMENT_ROOT'] . '/files/' . config('constants.aw_csv') . $filename;
        if (($handle = fopen($filePath, "r")) !== FALSE) {
            $escFilename = 'escaped_' . $filename;
            $newFileName = $_SERVER['DOCUMENT_ROOT'] . '/files/' . config('constants.aw_csv') . $escFilename;
            $filetowrite = fopen($newFileName, 'w');
            $firstRow = fgetcsv($handle, 1000, ",");
            $firstRow = array_map('trim', array_filter($firstRow));
            fputcsv($filetowrite, $firstRow);
            while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
                $ass_data = array_combine($firstRow, $data);
                //echo '<pre>';
                //print_r($ass_data);
                if (preg_match($mobileExp, $ass_data['Mobile'])) {
                    $speaker = Speaker::where('mobile', '=', $ass_data['Mobile'])->first();
                    if ($speaker) {
                        if (trim($ass_data['Designation'])) {
                            $speakerDetail = SpeakerDetails::where('designation', '=', trim($ass_data['Designation']))->where('company', '=', trim($ass_data['Organisation']))->where('speaker_id', '=', $speaker->id)->first();
                            //dd($speakerDetail);
                            if (!$speakerDetail) {
                                //echo '123'; exit;
                                SpeakerDetails::where('speaker_id', '=', $speaker->id)->update(['is_current' => 0]);
                                $speakerDetail = new SpeakerDetails;
                                $speakerDetail->speaker_id = $speaker->id;
                                $speakerDetail->designation = trim($ass_data['Designation']);
                                $speakerDetail->company = trim($ass_data['Organisation']);
                                $speakerDetail->mobiles = trim($ass_data['Phone']);
                                $speakerDetail->emails = trim($ass_data['Secondary Email']);
                                $speakerDetail->city = trim($ass_data['City']);
                                $speakerDetail->address = trim($ass_data['Address']);
                                $speakerDetail->is_current = '1';
                                $speakerDetail->save();
                            } else {
                                fputcsv($filetowrite, $ass_data);
                                $noOfEscapped++;
                            }
                        } else {
                            $speakerDetail = SpeakerDetails::where('speaker_id', '=', $speaker->id)->where('is_current', '=', '1')->first();
                        }

                        if ($speakerDetail) {
                            
                            $eventSpeakerRow = EventSpeaker::where('speaker_id','=',$speaker->id)->where('speaker_type_id', '=', $request->speaker_type)->where('event_id', '=', $eventId)->where('speaker_detail_id','=',$speakerDetail->id)->first();
                            if(!$eventSpeakerRow){
                                EventSpeaker::where('speaker_id','=',$speaker->id)->where('speaker_type_id', '=', $request->speaker_type)->where('event_id', '=', $eventId)->delete();
                                $eventSpeaker = new EventSpeaker();
                                $eventSpeaker->event_id = $eventId;
                                $eventSpeaker->speaker_type_id = $request->speaker_type;
                                $eventSpeaker->speaker_id = $speaker->id;
                                $eventSpeaker->speaker_detail_id = $speakerDetail->id;
                                $eventSpeaker->assigned_by = $uid;
                                $eventSpeaker->save();
                            }
                        }
                    } else {
                        $tagsId = '';
                        $speaker = new Speaker;
                        $speaker->name = $ass_data['Name'];
                        $speaker->email = $ass_data['Primary Email'];
                        $speaker->mobile = $ass_data['Mobile'];
                        $speaker->twitter = $ass_data['Twitter'];
                        $speaker->linkedin = $ass_data['LinkedIn'];
                        $speaker->description = $ass_data['Description'];
                        $speaker->tags = $tagsId;
                        $speaker->status = '1';
                        $speaker->save();
                        $speaker_id = $speaker->id;
                        if ($speaker_id){
                            if (trim($ass_data['Designation'])) {
                                $speakerDetail = new SpeakerDetails;
                                $speakerDetail->speaker_id = $speaker_id;
                                $speakerDetail->designation = trim($ass_data['Designation']);
                                $speakerDetail->company = trim($ass_data['Organisation']);
                                $speakerDetail->mobiles = trim($ass_data['Phone']);
                                $speakerDetail->emails = trim($ass_data['Secondary Email']);
                                $speakerDetail->city = trim($ass_data['City']);
                                $speakerDetail->address = trim($ass_data['Address']);
                                $speakerDetail->is_current = '1';
                                $speakerDetail->save();
                                $speaker_detail_id = $speakerDetail->id;
                                if ($speaker_detail_id) {
                                    $speaker = new EventSpeaker();
                                    $speaker->event_id = $eventId;
                                    $speaker->speaker_id = $speaker_id;
                                    $speaker->speaker_type_id = $request->speaker_type;
                                    $speaker->speaker_detail_id = $speaker_detail_id;
                                    $speaker->assigned_by = $uid;
                                    $speaker->save();
                                }
                            }
                        }
                        //echo '1 speaker added'; exit;
                    }
                } else {
                    $noOfEscapped++;
                    fputcsv($filetowrite, $ass_data);
                }
            }
        }

        fclose($handle);
        fclose($filetowrite);
        unlink($filePath);
        Session::flash('message', 'Speaker added successfully.');
        if ($noOfEscapped > 0) {
            $fileTran->tranferFile($escFilename, config('constants.aw_csv'), config('constants.aw_csv_escaped'), true, false);
            return Redirect::to('event/manage-speaker/'.$eventId.'?type='.$request->speaker_type.'&escapped=' . 'escapped_' . $filename);
        } else {
            return Redirect::to('event/manage-speaker/'.$eventId.'?type='.$request->speaker_type);
        }
    }
    
    public function activityLog(){
         $rightId = 109;
         if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
         
        $activities=ActivityLog::join('users','activity_logs.user_id','=','users.id')
                ->select('activity_logs.*','users.name')
                ->orderBy('created_at','DESC')->get();
        return view('events.activity-logs',compact('activities')); 
                 
    }

}
