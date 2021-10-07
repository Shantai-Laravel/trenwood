<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageTranslation extends Model
{
    protected $table = 'pages_translation';

    protected $fillable = [
        'page_id', 'lang_id', 'slug', 'title', 'body', 'image', 'meta_title', 'meta_description', 'meta_keywords',
    ];

    public function page() {
        return $this->belongsTo(Page::class);
    }
}
