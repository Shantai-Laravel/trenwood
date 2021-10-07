<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderProduct extends Model
{
    protected $fillable = ['product_id', 'subproduct_id', 'qty', 'set_id'];

    public function order() {
        return $this->hasOne(Order::class, 'id', 'order_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function subproduct() {
        return $this->hasOne(SubProduct::class, 'id', 'subproduct_id');
    }

    public function orderSet()
    {
        return $this->hasOne(OrderSet::class, 'id', 'set_id');
    }
}
