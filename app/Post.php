<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    

    use SoftDeletes;
    protected $fillable = ['title','description','content','thumbnail','creator','editor', 'user_id', 'slug', 'status', 'category_id'];

    function category(){
        return $this->belongsTo('App\CategoryPost' , 'category_id', 'id');
    }

    function user(){
        return $this->belongsTo('App\User');
    }
}
