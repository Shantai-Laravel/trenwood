<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductCategoryTranslation extends Model
{
    protected $table = 'product_categories_translation';

    protected $fillable = ['lang_id', 'product_category_id', 'name', 'descrition', 'seo_text', 'seo_title', 'seo_description', 'seo_keywords'];

    public function menu() {

        return $this->belongsTo(ProductCategory::class);
    }
}
