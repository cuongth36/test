<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['product_id','order_id', 'qty', 'price','color', 'size'];

    function order(){
        $this->belongsTo('App\Order');
    }
}
