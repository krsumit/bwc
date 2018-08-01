<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LiveFeed extends Model
{
    protected $primaryKey = 'id';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
