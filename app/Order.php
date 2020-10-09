<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'email', 'phone','order_code', 'address', 'note', 'total', 'customer_id','shipping', 'payments', 'status'];

    function orderDetail(){
        return $this->hasMany('App\OrderDetail');
    }

    function products(){
        return $this->belongsToMany('App\Product', 'order_details', 'order_id', 'product_id');
    }

    
}
