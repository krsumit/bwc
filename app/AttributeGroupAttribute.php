<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AttributeGroupAttribute extends Model
{

    use SoftDeletes;
    //protected $dates = ['deleted_at'];
}
