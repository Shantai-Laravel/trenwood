<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProductValue extends Model
{
    protected $table = 'subproduct_values';

    protected $fillable = ['sub_product_id', 'property_id', 'property_value_id'];

    public function subproduct() {
        return $this->belongsTo(SubProduct::class, 'sub_product_id', 'id');
    }

    public function subproduct_properties() {
        return $this->belongsTo(SubProductProperty::class, 'property_id', 'property_id');
    }
}
