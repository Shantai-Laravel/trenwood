<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Traduction extends Model
{
    protected $table = 'traductions';

    protected $fillable = ['page_id', 'number'];

    public function translations()
    {
        return $this->hasMany(TraductionTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(TraductionTranslation::class, 'traduction_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang, $traductionId)
    {
        return $this->hasOne(TraductionTranslation::class)->where('lang_id', $lang)->where('traduction_id', $traductionId)->first();
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(TraductionTranslation::class)->where('lang_id', $lang);
    }
}
