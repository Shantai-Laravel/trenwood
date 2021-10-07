<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartSet extends Model
{

    protected $table = 'cart_sets';

    protected $fillable = ['set_id', 'user_id', 'qty', 'is_logged', 'price'];

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'set_id', 'id');
    }

}
