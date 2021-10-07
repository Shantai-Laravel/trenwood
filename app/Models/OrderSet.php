<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSet extends Model
{
    protected $fillable = ['set_id', 'qty', 'price'];

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function orderProduct()
    {
        return $this->hasMany(OrderProduct::class, 'set_id', 'id');
    }
}
