<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturProduct extends Model
{
    protected $table = 'return_products';
    protected $fillable = ['orderProduct_id', 'product_id', 'subproduct_id', 'qty', 'set_id'];

    public function orderProduct() {
        return $this->hasOne(OrderProduct::class, 'id', 'orderProduct_id');
    }

    public function product() {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function subproduct() {
        return $this->hasOne(SubProduct::class, 'id', 'subproduct_id');
    }
}
