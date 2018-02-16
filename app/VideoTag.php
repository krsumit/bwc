<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VideoTag extends Model
{
    //
    protected $table = 'video_tags';
    protected $primaryKey = 'v_tags_id';
    public $timestamps = false;
}
