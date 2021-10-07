<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyCategory extends Model
{
    protected $table = 'properties_categories';

    protected $fillable = ['property_id', 'category_id'];

    public function property() {
        return $this->hasOne(Property::class);
    }

    public function category() {
        return $this->hasOne(ProductCategory::class);
    }
}
