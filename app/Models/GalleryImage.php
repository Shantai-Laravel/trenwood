<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GalleryImage extends Model
{
    protected $table = 'gallery_images';

    protected $fillable = ['gallery_id', 'src'];

    public function translations() {
        return $this->hasMany(GalleryImageTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(GalleryImageTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasOne(GalleryImageTranslation::class)->where('lang_id', $lang);
    }
}
