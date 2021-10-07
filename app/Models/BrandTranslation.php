<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandTranslation extends Model
{
    protected $table = 'brands_translation';

    protected $fillable = [
        'brand_id', 'lang_id', 'name', 'grapes', 'production', 'temperature', 'description', 'banner', 'seo_text',
        'seo_title', 'seo_descr', 'seo_keywords'
    ];

    public function page() {
        return $this->belongsTo(Brand::class);
    }
}
