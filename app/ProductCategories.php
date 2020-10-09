<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductCategories extends Model
{
    //
    
    protected $fillable = ['title', 'slug','link', 'thumbnail', 'parent_id'];
    
    function product(){
        return $this->hasMany('App\Product','category_id', 'id');
    }
}
