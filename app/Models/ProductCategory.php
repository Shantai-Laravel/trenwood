<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $table = 'product_categories';

    protected $fillable = ['parent_id', 'alias', 'level', 'position', 'img', 'video', 'banner_1', 'banner_2', 'on_home', 'group_id'];

    public function translations() {
        return $this->hasMany(ProductCategoryTranslation::class);
    }

    public function translation($lang = 1)
    {
        return $this->hasMany(ProductCategoryTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(ProductCategoryTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(ProductCategoryTranslation::class)->where('lang_id', $lang);
    }

    public function properties() {
        return $this->hasMany(SubProductProperty::class, 'product_category_id', 'id')->where('show_property', 1);
    }

    public function propertyMain() {
        return $this->hasOne(SubProductProperty::class, 'product_category_id', 'id')->where('show_property', 1)->where('image', 1);
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent()
    {
        return $this->belongsTo(ProductCategory::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ProductCategory::class, 'parent_id');
    }
}
