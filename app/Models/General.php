<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class General extends Model
{
    protected $table = 'general';

    protected $fillable = ['name'];

    public function translations()
    {
        return $this->hasMany(GeneralTranslation::class);
    }

    public function translationByLanguage($lang = '1')
    {
        return $this->hasOne(GeneralTranslation::class)->where('lang_id', $lang);
    }
}
