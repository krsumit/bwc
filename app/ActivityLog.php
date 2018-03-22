<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;

class ActivityLog extends Model {

    protected $table = "activity_logs";

    /**
     * Get the users associated with the given right.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
   
    // To store activities 
    public function storeActivity($message,$data) {
      $userId=Session::get('users')->id;
      $this->notification=$message;
      $this->notification_info=$message;
      $this->user_id=$userId;
      $this->save();
        
    }

    

}
