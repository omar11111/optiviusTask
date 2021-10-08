<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model 
{

    protected $table = 'Articles';
    public $timestamps = true;
    protected $fillable = array('title', 'content','user_id');

    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }

}