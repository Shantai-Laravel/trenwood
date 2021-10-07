<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPropertyTranslation extends Model
{
    protected $table = 'product_properies_translation';

    protected $fillable = [
        'property_id',
        'lang_id',
        'name',
        'multi_data',
        'value',
        'unit'
    ];

    public function productProperty()
    {
        return $this->belongsTo(ProductProperty::class, 'property_id');
    }
}
