<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Size extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'creator', 'status'];

    function products(){
        return $this->belongsToMany('App\Product', 'product_attribute');
    }

    function productAtrribute(){
        return $this->hasMany('App\ProductAttribute');
    }
}
