<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubProductProperty extends Model
{
    protected $table = 'subproduct_properties';

    protected $fillable = ['product_category_id', 'property_id', 'show_property', 'status', 'image'];

    public function values() {
        return $this->hasMany(SubProductValue::class, 'property_id', 'property_id');
    }

    public function property()
    {
        return $this->hasOne(ProductProperty::class, 'id', 'property_id');
    }
}
