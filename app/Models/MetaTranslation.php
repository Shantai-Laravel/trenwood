<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MetaTranslation extends Model
{
    protected $table = 'metas_translation';

    protected $fillable = ['lang_id', 'meta_id', 'title', 'keywords', 'description'];

    public function meta() {
    	return $this->belongsTo(Meta::class);
    }
}
