<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    //
   protected $fillable = ['title', 'parent_id', 'slug', 'link', 'menu_sort'];
   
   function multiChildMenu(){
       return $this->hasMany('App\Menu', 'parent_id');
   }
}
