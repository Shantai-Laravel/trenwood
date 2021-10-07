<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductTranslation extends Model
{
    protected $table = 'product_translations';

    protected $fillable = [
        'lang_id',
        'product_id',
        'name',
        'description',
        'body',
        'alias',
        'seo_title',
        'seo_keywords',
        'seo_description',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
