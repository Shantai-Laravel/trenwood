<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class FrontUser extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'front_users';

    protected $fillable = [
        'name', 'user_email', 'status', 'user_hash', 'password', 'remember_token', 'coef',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
}
