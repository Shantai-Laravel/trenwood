<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostTranslation extends Model
{
    protected $table = 'posts_translation';

    protected $fillable = [
        'lang_id',
        'post_id',
        'title',
        'body',
        'slug',
        'url',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'video',
        'image',
        'image_title',
        'image_alt'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
}
