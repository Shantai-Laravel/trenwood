<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubproductCombination extends Model
{
    protected $table = 'subproduct_combinations';

    protected $fillable = ['category_id', 'case_1', 'case_2', 'case_3'];

    // public function subproduct() {
    //     return $this->belongsTo(SubProduct::class, 'sub_product_id', 'id');
    // }
    //
    // public function subproduct_properties() {
    //     return $this->belongsTo(SubProductProperty::class, 'property_id', 'property_id');
    // }
}
