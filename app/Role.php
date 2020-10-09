<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    //
    protected $fillable = ['name', 'description', 'key_code'];

    function users(){
        return $this->belongsToMany('App\User', 'user_role', 'user_id', 'role_id')->withTimestamps();
    }
}
