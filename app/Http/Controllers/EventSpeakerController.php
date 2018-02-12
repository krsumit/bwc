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
        $speakers = Speaker::orderBy('updated_at', 'desc');
       
        if (isset($_GET['searchin'])) {
            if ($_GET['searchin'] == 'name') {
                $speakers->where('name', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (@$_GET['searchin'] == 'email') {
                $speakers->where('email', 'like', '%' . $_GET['keyword'] . '%');
            }
        }
        $speakers = $speakers->get();
        return view('events.speaker', compact('speakers'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function create() {
        $rightId = 2;
        $currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);
        if (!$this->rightObj->checkRights($currentChannelId, $rightId))
            return redirect('/dashboard');

        return view('events.add-speaker');
    }

    public function store(Request $request) {
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }

        /* Right mgmt start */
        $rightId = 2;
        //$currentChannelId = $request->channel;
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        /* Right mgmt end */
        //dd($request->all());

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

       
        $imageurl = '';

        if ($request->file('photo')) { // echo 'test';exit;
            $filename = str_random(6) . '_' . $request->file('photo')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $imageurl = $filename;
            $fileTran->uploadFile($request->file('photo'), config('constants.awspeakerdir'), $filename);
        }

        if (!empty($imageurl)) {
            $photo = $imageurl;
        } else {
            $photo = '';
        }

        $speaker = new Speaker;
        $speaker->name = $request->speaker_name;
        $speaker->email = $request->speaker_email;
        $speaker->mobile = $request->speaker_mobile;
        $speaker->photo = $request->speaker_name;
        $speaker->twitter = $request->speaker_twitter;
        $speaker->linkedin = $request->speaker_linkedin;
        $speaker->description = $request->speaker_desc;
        $speaker->tags = $request->Taglist;
        $speaker->status = '1';
        $speaker->save();
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
                $speakerDetail->save();
            }
        }

        Session::flash('message', 'Speaker added successfully.');
        return Redirect::to('speaker');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id){
        $speaker=  Speaker::find($id);
        //echo $speaker->speaker_id; exit;
        $speakerDetails =  SpeakerDetails::where('speaker_id','=',$speaker->id)->get();
        //dd($speakerDetails);
        return view('events.info-speaker',compact('speaker','speakerDetails'));
        
    }
  
  
    public function edit($id) {
       
        if (!Session::has('users')) {
            return redirect()->intended('/auth/login');
        }
        /* Right mgmt start */
        $rightId = 50;
        $channels = $this->rightObj->checkRightsIrrespectiveChannel($rightId);
        $speaker= Speaker::find($id);
        $profileId='';
        if(isset($_GET['profile'])){
            $profileId=$_GET['profile'];
        }else{
            $profileId=SpeakerDetails::where('speaker_id','=',$speaker->id)->where('is_current','=','1')->first()->id;
        }
        $speakerDetails=  SpeakerDetails::where('speaker_id','=',$speaker->id)->orderBy('is_current','desc')->get();  //->get();
        //echo json_encode($speakerDetails); exit;
        return view('events.edit-speaker',compact('speaker','speakerDetails','profileId'));
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


        // echo $id; die;
        //fwrite($asd, " Del Ids: ".$id." \n\n");
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
        $speakers = Speaker::where('event_id', '=', $id)
                ->orderBy('updated_at', 'desc');

        if (isset($_GET['searchin'])) {
            if ($_GET['searchin'] == 'name') {
                $speakers->where('name', 'like', '%' . $_GET['keyword'] . '%');
            }
            if (@$_GET['searchin'] == 'email') {
                $speakers->where('email', 'like', '%' . $_GET['keyword'] . '%');
            }
        }

        $speakers = $speakers->get();

        return view('events.event-speaker', compact('event', 'speakers'));
    }

    public function storeEventSpeaker(Request $request) {
        $filename = '';
        if ($request->file('speaker_image')) {
            $file = $request->file('speaker_image');
            $filename = str_random(6) . '_' . $request->file('speaker_image')->getClientOriginalName();
            $name = $request->name;
            $fileTran = new FileTransfer();
            $fileTran->uploadFile($file, config('constants.awspeakerdir'), $filename);
        }

        $speaker = new Speaker();
        $speaker->event_id = $request->event_id;
        $speaker->name = $request->speaker_name;
        $speaker->email = $request->speaker_email;
        $speaker->photo = $filename;
        $speaker->twitter = $request->speaker_twitter;
        $speaker->description = $request->speaker_desc;
        $speaker->tag = $request->Taglist;
        $speaker->save();
        Session::flash('message', 'Speaker added successfully');
        return Redirect::to('event/manage-speaker/' . $request->event_id);
    }

    public function editEventSpeaker($id) {
        $speaker = Speaker::find($id);
        $event = Event::find($speaker->event_id);
        $tagsId = explode(',', $speaker->tag);
        $tags = json_encode(DB::table('speaker_tags')
                        ->select('speaker_tags.tags_id as id', 'speaker_tags.tag as name')
                        ->where('speaker_tags.valid', '1')
                        ->whereIn('tags_id', $tagsId)
                        ->get());

        return view('events.edit-event-speaker', compact('event', 'speaker', 'tags'));
    }

    public function updateEventSpeaker(Request $request) {
        $speaker = Speaker::find($request->speaker_id);
        $speaker->event_id = $request->event_id;
        $speaker->name = $request->speaker_name;
        $speaker->email = $request->speaker_email;
        $speaker->twitter = $request->speaker_twitter;
        $speaker->description = $request->speaker_desc;
        $speaker->tag = $request->Taglist;

        if ($request->file('speaker_image')) {
            $file = $request->file('speaker_image');
            $filename = str_random(6) . '_' . $request->file('speaker_image')->getClientOriginalName();
            $fileTran = new FileTransfer();
            $fileTran->uploadFile($file, config('constants.awspeakerdir'), $filename);
            if (trim($speaker->photo)) {
                $fileTran->deleteFile(config('constants.awspeakerdir'), $speaker->photo);
            }
            $speaker->photo = $filename;
        }
        $speaker->save();
        Session::flash('message', 'Speaker updated successfully');
        return Redirect::to('event/manage-speaker/' . $request->event_id);
    }

    public function apiEventSpeaker($id) {
        $id = base64_decode($id);
        $event = Event::leftJoin('country', 'event.country', '=', 'country.country_id')
                ->leftJoin('country_states', 'event.state', '=', 'country_states.state_id')
                ->where('event.event_id', '=', $id)
                ->select('event_id', 'title', 'description', 'imagepath', 'start_date', 'end_date', 'country.name as country', 'country_states.name as state')
                ->first();
        $event->imagepath = config('constants.awsbaseurl') . config('constants.awaevent') . $event->imagepath;

        $speakers = Speaker::where('event_id', '=', $id)->where('status', '=', '1')->get();
        $speakersArray = array();
        foreach ($speakers as $speaker) {
            $tagsId = explode(',', $speaker->tag);
            $tags = SpeakerTag::whereIn('tags_id', $tagsId)->get();
            $tgs = '';
            foreach ($tags as $tag) {
                $tgs = trim($tgs) ? ',' . $tag->tag : $tag->tag;
            }
            $speakersArray[] = array('id' => $speaker->id, 'name' => $speaker->name, 'email' => $speaker->email, 'photo' => config('constants.awsbaseurl') . config('constants.awspeakerdir') . $speaker->photo, 'twitter' => $speaker->twitter, 'description' => $speaker->description, 'tag' => $tgs);
        }

        $data['event'] = $event;
        $data['speakers'] = $speakersArray;
        //cho '<pre>';
        //print_r($data);
        return json_encode($data);
    }

    public function storeTag(Request $request) {

        //Save Request Tuple in Table - Validate First

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
        //DB::enableQueryLog();
        $matchText = $_GET['q'];
        $tag = new SpeakerTag;
        //->all()
        $rst = $tag->where('tag', "like", $matchText . '%')->select('tags_id as id', 'tag as name')->get();
        return response()->json($rst);
    }

}
