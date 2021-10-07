<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVideo extends Model
{
    protected $table = 'product_videos';

    protected $fillable = ['product_id'];

    public function translations() {
        return $this->hasMany(ProductVideoTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ProductVideoTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(ProductVideoTranslation::class, 'product_video_id')->where('lang_id', $lang);
    }
}
