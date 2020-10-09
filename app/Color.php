<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Color extends Model
{
    use SoftDeletes;
    protected $fillable = ['title','status', 'color_code', 'creator'];
    function productAtrribute(){
        return $this->hasMany('App\ProductAttribute');
    }
}
