<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    
    protected $fillable = ['name','email','title', 'status','content'];

    function user(){
        return $this->belongsTo('App\User');
    }
}
