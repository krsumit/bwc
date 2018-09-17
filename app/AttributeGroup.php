<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class AttributeGroup extends Model
{

    use SoftDeletes;
    //protected $dates = ['deleted_at'];
}
