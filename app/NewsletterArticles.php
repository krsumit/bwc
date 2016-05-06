<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NewsletterArticles extends Model
{
    protected $table = 'master_newsletter_articles';
    protected $primaryKey = 'id';
    public $timestamps = false;
   
}
