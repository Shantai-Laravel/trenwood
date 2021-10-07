<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $table = 'brands';

    protected $fillable = ['parent_id', 'alias', 'img', 'picture'];

    public function translations()
    {
        return $this->hasMany(BrandTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(BrandTranslation::class, 'brand_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = '1')
    {
        return $this->hasOne(BrandTranslation::class)->where('lang_id', $lang);
    }
}
