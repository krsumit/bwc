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

class NewsletterSubscriberController extends Controller {

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
        $rightId = 95;
        //$currentChannelId = $this->rightObj->getCurrnetChannelId($rightId);
        $channels = $this->rightObj->getAllowedChannels($rightId);

        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        $subscriberChannel = 0;
        if (isset($_GET['channel'])) { // If channel id passed in get it will return that id
            $subscriberChannel = $_GET['channel'];
        }
        $channelIdArray = array();
        foreach ($channels as $channel) {
            $channelIdArray[] = $channel['channel_id'];
        }
        $query = DB::table('subscribers as sub')->join('subscriber_newsletter as subn', 'sub.id', '=', 'subn.id')->whereIn('channel_id', $channelIdArray);

        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $query->where(function ($query) use ($queryed) {
                $query->where('sub.first_name', 'LIKE', '%' . $queryed . '%')
                        ->orWhere('sub.first_name', 'LIKE', '%' . $queryed . '%')
                        ->orWhere('sub.email', 'LIKE', '%' . $queryed . '%');
            });

            //('sub.first_name', 'LIKE', '%' . $queryed . '%');
        }
        if ($subscriberChannel) {
            $query->where('subn.channel_id', '=', $subscriberChannel);
        }


        $query->where('is_deleted', '=', '0');
        $query->orderby('subn.updated_at', 'desc');

        $subscribers = $query->paginate(config('constants.recordperpage'));
        //echo count($subscribers); exit;
        return view('newsletter.index', compact('subscribers', 'channels', 'subscriberChannel'));
    }

    public function exportCsv(Request $request) {
        $rightId = 95;
        $channels = $this->rightObj->getAllowedChannels($rightId);
        
        if (!$this->rightObj->checkRightsIrrespectiveChannel($rightId))
            return redirect('/dashboard');
        
        $subscriberChannel = 0;
        
        if (isset($_GET['channel'])) { // If channel id passed in get it will return that id
            $subscriberChannel = $_GET['channel'];
        }
        $channelIdArray = array();
        foreach ($channels as $channel) {
            $channelIdArray[] = $channel['channel_id'];
        }
        $query = DB::table('subscribers as sub')->join('subscriber_newsletter as subn', 'sub.id', '=', 'subn.id')->whereIn('channel_id', $channelIdArray);
        if (isset($_GET['keyword'])) {
            $queryed = $_GET['keyword'];
            $query->where(function ($query) use ($queryed) {
                $query->where('sub.first_name', 'LIKE', '%' . $queryed . '%')
                        ->orWhere('sub.last_name', 'LIKE', '%' . $queryed . '%')
                        ->orWhere('sub.email', 'LIKE', '%' . $queryed . '%');
            });

        }
        if ($subscriberChannel) {
            $query->where('subn.channel_id', '=', $subscriberChannel);
        }
        $query->where('is_deleted', '=', '0');
        $query->orderby('sub.first_name', 'desc');
        $subscribers = $query->get();
        
             
        $export_data="Email,Name\n";
        foreach($subscribers as $sub){
            $export_data.=$sub->email.','.$sub->first_name.' '.$sub->last_name."\n";
        }
        return response($export_data)
            ->header('Content-Type','application/csv')               
            ->header('Content-Disposition', 'attachment; filename="download.csv"')
            ->header('Pragma','no-cache')
            ->header('Expires','0');                     
       
       // echo count($subscribers); exit;
    }

}
