<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = ['email', 'password', 'address', 'phone','name', 'is_active', 'active_token'];
}
