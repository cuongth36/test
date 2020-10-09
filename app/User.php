<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    function roles(){
        return $this->belongsToMany('App\Role', 'user_role', 'user_id', 'role_id')->withTimestamps();
    }

    function hasAccess($role){
        $user = User::find(Auth::id());
        $user_role = $user->roles;
        $key_code = [];
        foreach($user_role as $item){
            $key_code[] = $item->key_code;
           if(in_array($role,$key_code)){
               return true;
           }
        }
        return false;
    }
}
