<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model 
{

    protected $table = 'users';
    public $timestamps = true;
    protected $fillable = array('name', 'phone', 'email', 'password','code', 'email_verified_at', 'phone_verfied_at');
    protected  $hidden=['password','api_token','code'];
    
    public function articles()
    {
        return $this->hasMany('App\Models\Article',"user_id", "id");
    }

}