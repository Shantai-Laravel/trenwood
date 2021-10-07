<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontUserUnlogged extends Model
{
    protected $table = 'front_users_unlogged';

    protected $fillable = ['name', 'email', 'phone', 'spam'];

    public function addresses() {
        return $this->hasMany(FrontUserAddress::class);
    }
}
