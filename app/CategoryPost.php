<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoryPost extends Model
{
  
    protected $fillable = ['title', 'parent_id', 'slug'];
    
    public function posts(){
        return $this->hasMany('App\Post', 'category_id', 'id');
    }
}
