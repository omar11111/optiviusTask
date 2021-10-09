<?php

namespace App\Models;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class Article extends Model 
{
    use HasTranslations;
    public $translatable = ['title','content'];
    protected $table = 'Articles';
    public $timestamps = true;
    protected $fillable = array('title', 'content','user_id');

    public function users()
    {
        return $this->belongsTo('App\Models\User');
    }

}