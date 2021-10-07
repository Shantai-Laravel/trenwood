<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $table = 'promotions';

    protected $fillable = ['alias', 'img', 'discount'];

    public function translations()
    {
        return $this->hasMany(PromotionTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(PromotionTranslation::class, 'promotion_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = '1')
    {
        return $this->hasOne(PromotionTranslation::class)->where('lang_id', $lang);
    }
}
