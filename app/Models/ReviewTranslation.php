<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewTranslation extends Model
{
    protected $table = 'reviews_translation';

    protected $fillable = ['review_id', 'lang_id', 'author', 'text', 'alt', 'title'];

    public function review() {
        return $this->belongsTo(Review::class);
    }
}
