<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','slug', 'description', 'content', 'price', 'product_hot', 'discount', 'thumbnail', 'status', 'category_id' , 'quantity'];

  
    function feature_image(){
        return $this->hasMany('App\FeatureImage');
    }

    function productAttribute(){
        return $this->hasMany('App\ProductAttribute');
    }

    function order(){
        return $this->belongsToMany('App\Order', 'order_details', 'order_id','product_id')->withTimestamps();
    }

    function categories(){
        return $this->belongsTo('App\ProductCategories', 'category_id', 'id');
    }

}
