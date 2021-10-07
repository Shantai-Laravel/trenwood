<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TraductionTranslation extends Model
{
    protected $table = 'traductions_translations';

    protected $fillable = [
        'traduction_id', 'lang_id', 'value'
    ];

    public function page() {
        return $this->belongsTo(Traduction::class);
    }
}
