<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Right extends Model
{
    /**
     * Get the users associated with the given right.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User',"right_user");
    }
    
}
