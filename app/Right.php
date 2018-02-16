<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\UserRight;
use App\Channel;
use App\UserChannelRight;
use Session;
use Illuminate\Http\Request;
use App\Http\Requests;

class Right extends Model {

    protected $table = "rights";

    /**
     * Get the users associated with the given right.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users() {
        return $this->belongsToMany('App\User', "right_user");
    }

    // Function to check user irghts. @arg1 : Right id to check @arg2 (Optional) : Channel id to check
    public function checkRights($channelId, $rightId = '') {
        if(!Session::get('users')->id){
            return false;
        }
        $uid = Session::get('users')->id;
        if (trim($rightId)) { // This is the case where channelid and rightid both are available
            $count = count(UserRight::where('right_for', '1')->where('user_id', $uid)->where('channel_id', $channelId)->where('rights_id', $rightId)->get());
        } else { // Case where use get right by defautl if user have right to access that channel
            $count = count(UserChannelRight::select('user_channels_right.channel_id')
                            ->where('user_channels_right.user_id', $uid)
                            ->where('user_channels_right.channel_id', $channelId)
                            ->get());
        }
        if ($count == 0) {
            Session::flash('error', 'You are not authorized to access.');
            return false;
        }
        return true;
    }

    // This function is being used to check rights for those section which are independent of channel e.g author. 
    public function checkRightsIrrespectiveChannel($rightId) {
       if(!Session::get('users')->id){
            return false;
        }
        $uid = Session::get('users')->id;
        
        $count = count(UserRight::where('right_for', '1')
                        ->where('user_id', $uid)
                        ->where('rights_id', $rightId)->get());
        if ($count == 0) {
            Session::flash('error', 'You are not authorized to access.');
            return false;
        }
        return true;
    }

    //This function will return channels which are allowed to that user.
    //@arg1(optional) : If right id passed it will return only those channel which have that right for the user.
    public function getAllowedChannels($rightId = '') {
        $uid = Session::get('users')->id;
        if (trim($rightId)) {
            $userChannels = UserRight::join('channels', 'user_rights.channel_id', '=', 'channels.channel_id')
                    ->select('channels.channel_id', 'channels.channel')
                    ->where('user_rights.user_id', $uid)
                    ->where('user_rights.right_for', '1')
                    ->where('user_rights.rights_id', $rightId)
                    ->where('channels.valid', '1')
                    ->groupBy('user_rights.channel_id')
                    ->orderBy('channels.channel')
                    ->get();
        } else {
            $userChannels = UserChannelRight::join('channels', 'user_channels_right.channel_id', '=', 'channels.channel_id')
                    ->select('channels.channel_id', 'channels.channel')
                    ->where('user_channels_right.user_id', $uid)
                    ->where('channels.valid', '1')
                    ->orderBy('channels.channel')
                    ->get();
        }
        return $userChannels;
    }

    // It will return the current channel of a user. If a channel id exist in url it will return that channel
    //Otherwise return the first chanenl from the allowed channel.
    // @arg1 : If you want to get the channel id for specified right pass the right id.
    public function getCurrnetChannelId($rightId = '') {
        $uid = Session::get('users')->id;
        if (isset($_GET['channel'])) { // If channel id passed in get it will return that id
            return $_GET['channel'];
        } else {
            if (trim($rightId)) {// IF user pass rightid it will return the first channel with that rights
                $userChannel = UserRight::join('channels', 'user_rights.channel_id', '=', 'channels.channel_id')
                        ->select('channels.channel_id', 'channels.channel')
                        ->where('user_rights.user_id', $uid)
                        ->where('user_rights.right_for', '1')
                        ->where('user_rights.rights_id', $rightId)
                        ->groupBy('user_rights.channel_id')
                        ->orderBy('channels.channel')
                        ->first();
                if ($userChannel)
                    return $userChannel->channel_id;
            }else {// If user doesn't pass rightId it will return the first channel id of all allowed channels 
                $userChannel = UserChannelRight::join('channels', 'user_channels_right.channel_id', '=', 'channels.channel_id')
                        ->select('channels.channel_id', 'channels.channel')
                        ->where('user_channels_right.user_id', $uid)
                        ->orderBy('channels.channel')
                        ->first();
                if ($userChannel)
                    return $userChannel->channel_id;
            }
        }
        return 0;
    }

}
