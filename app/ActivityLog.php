<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\EventSpeaker;
use App\SpeakerTag;
use DB;
use App\SpeakerDetails;

class ActivityLog extends Model {

    protected $table = "activity_logs";

    /**
     * Get the users associated with the given right.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   
    /* To store activities  
     * $secton is string and 
     * $data array of data
     */
    public function storeActivity($action,$section,$data,$extra_data='') {
        
      switch($action):
          case 'create':
              switch($section):
                case 'attendee': // Table : speakers , action : insert
                    $message='Added attendee:'.$data->name;
                    $data_to_store=array('attende_id'=>$data->id);
                    break;
                case 'attendee_profile': // Table : speaker_details , action : insert
                    $message='Added profile for:'.$extra_data->name.' ('.$data->company.'-'.$data->designation.')';
                    $data_to_store=array('attende_id'=>$extra_data->id,'profile_id'=>$data->id);
                    break;
                case 'event': // Table : event , action : insert
                    $message='Added event:'.$data->title;
                    $data_to_store=array('event_id'=>$data->event_id);
                    break;
                case 'event_speaker': // Table : event_speaker , action : insert                   
                        $eventSpeaker=DB::table('event_speaker')
                        ->join('speakers','event_speaker.speaker_id','=','speakers.id')
                        ->join('speaker_details', 'event_speaker.speaker_detail_id', '=', 'speaker_details.id')
                        ->Join('speaker_type','event_speaker.speaker_type_id','=','speaker_type.id')  
                        ->join('event','event_speaker.event_id','=','event.event_id')
                        ->select('event.title','speakers.name','speaker_details.designation','speaker_details.company','speaker_type.name as type')
                        ->where('event_speaker.id', "=", $data->id)
                        ->first();                   
                        $message='Added '.$eventSpeaker->type.' in "'.$eventSpeaker->title.'", Details: '.$eventSpeaker->name.'('.$eventSpeaker->designation.'-'.$eventSpeaker->company.')';
                        $data_to_store=array('event_speaker_id'=>$data->id);
                    break;
                case 'speaker_tag': //Table : event_speaker , action : insert
                    $message='Added tag:'.$data->tag;
                    $data_to_store=array('id'=>$data->tags_id);
                    break;
                endswitch;
              
              break;
          case 'update': 
                $oldArray=$data->getOriginal();
                $newArray=$data->getAttributes();
                $old=array_diff_assoc($oldArray,$newArray);
                $new=array_diff_assoc($newArray,$oldArray);
              switch($section):
                case 'attendee': // Table : speakers , action : insert
                    if(count($old)>0){
                         $message='Edited attendee:'.$data->name;
                         $data_to_store=array('attende_id'=>$data->id,'old'=>$old,'new'=>$new);
                    }
                    break;
                case 'attendee_profile': // Table : speakers , action : insert
                    //dd($data);
                    if(count($old)>0){
                        $message='Edited profile of :'.$extra_data->name.' ('.$data->company.'-'.$data->designation.')';
                        $data_to_store=array('attende_id'=>$extra_data->id,'profile_id'=>$data->id,'old'=>$old,'new'=>$new);
                    }
                    break;
                case 'event':
                    if(count($old)>0){
                         $message='Edited event:'.$data->title;
                         $data_to_store=array('event_id'=>$data->event_id,'old'=>$old,'new'=>$new);
                    }
                    break;             
                case 'event_speaker': // Table : event_speaker , action : insert
                        //dd($data);
                        if(count($old)>0){
                             $eventSpeaker=DB::table('event_speaker')
                            ->join('speakers','event_speaker.speaker_id','=','speakers.id')
                            ->Join('speaker_type','event_speaker.speaker_type_id','=','speaker_type.id')  
                            ->join('event','event_speaker.event_id','=','event.event_id')
                            ->select('event.title','speakers.name','speaker_type.name as type')
                            ->where('event_speaker.id', "=", $data->id)
                            ->first();
                            $speakerDetail = SpeakerDetails::find($newArray['speaker_detail_id']);
                            $message='Updated '.$eventSpeaker->type.' in "'.$eventSpeaker->title.'", Details: '.$eventSpeaker->name.'('.$speakerDetail->designation.'-'.$speakerDetail->company.')';
                            $data_to_store=array('event_speaker_id'=>$data->id,'old'=>$old,'new'=>$new);
                        }
                    break;
                
              endswitch;
              
              break;
          case 'delete':
              switch($section):
                case 'attendee':
                    $message='Deleted attendee:'.$data->name;
                    $data_to_store=array('attende_id'=>$data->id);
                    break;
                case 'attendee_profile':
                    break;
                case 'event':
                    $message='Deleted event:'.$data->title;
                    $data_to_store=array('event_id'=>$data->event_id);
                    break;
                case 'event_speaker':
                            // dd($data);
                            $eventSpeaker=DB::table('event_speaker')
                            ->join('speakers','event_speaker.speaker_id','=','speakers.id')
                            ->join('speaker_details', 'event_speaker.speaker_detail_id', '=', 'speaker_details.id')
                            ->Join('speaker_type','event_speaker.speaker_type_id','=','speaker_type.id')  
                            ->join('event','event_speaker.event_id','=','event.event_id')
                            ->select('event.title','speakers.name','speaker_details.designation','speaker_details.company','speaker_type.name as type')
                            ->where('event_speaker.id', "=", $data->id)
                            ->first();

                            $message='Deleted '.$eventSpeaker->type.' in "'.$eventSpeaker->title.'", Details: '.$eventSpeaker->name.'('.$eventSpeaker->designation.'-'.$eventSpeaker->company.')';
                            $data_to_store=array('event_speaker_id'=>$data->id);
                    break;
              endswitch;
              
              break;
          
      endswitch;
        
      //print_r($data->getOriginal()); exit;
      if(isset($message)){
        $userId=Session::get('users')->id;
        $this->notification=$message;
        $this->notification_info=json_encode($data_to_store);
        $this->user_id=$userId;
        $this->save();  
      }
      //echo json_encode($data).'<br>';
    }
    
     public function storeActivityCustom($message,$data) {
      $userId=Session::get('users')->id;
      $this->notification=$message;
      $this->notification_info=json_encode($data);
      $this->user_id=$userId;
      $this->save();  
      //echo json_encode($data).'<br>';
    }

    

}
