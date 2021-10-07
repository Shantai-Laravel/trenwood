<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductProperty extends Model
{
    protected $table = 'product_properies';

    protected $fillable = ['type', 'multilingual', 'key', 'group_id'];

    public function translations() {
        return $this->hasMany(ProductPropertyTranslation::class, 'property_id');
    }

    public function category($categoryId) {
        return $this->hasOne(ProductCategoryTranslation::class, 'product_category_id', 'category_id')->where('product_category_id', $categoryId);
    }

    public function group() {
        return $this->hasOne(PropertyGroup::class, 'id', 'group_id');
    }

    public function multidata() {
        return $this->hasMany(PropertyMultiData::class, 'property_id');
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ProductPropertyTranslation::class, 'property_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasMany(ProductPropertyTranslation::class, 'property_id')->where('lang_id', $lang);
    }
}
