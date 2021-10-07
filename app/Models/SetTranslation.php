<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetTranslation extends Model
{
    protected $table = 'sets_translation';

    protected $fillable = [
        'lang_id', 'set_id', 'name', 'addInfo', 'description', 'image', 'seo_text',
        'seo_title', 'seo_description', 'seo_keywords'
    ];

    public function collection() {
        return $this->belongsTo(Set::class);
    }
}
