<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CategoryTranslation extends Model
{
    protected $fillable = ['lang_id', 'name', 'description', 'slug', 'meta_title', 'meta_keywords', 'meta_description', 'alt_attribute', 'image_title'];

    protected $table = 'categories_translation';

    public function category() {

        return $this->belongsTo(Category::class);
    }
}
