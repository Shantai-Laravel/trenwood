<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $table = 'galleries';

    protected $fillable = ['alias'];

    public function MainImage()
    {
        return $this->hasOne(GalleryImage::class, 'gallery_id', 'id')->where('gallery_id', $this->id);
    }

    public function Images()
    {
        return $this->hasMany(GalleryImage::class);
    }

    // public function translation()
    // {
    //     $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;
    //
    //     return $this->hasMany(BrandTranslation::class, 'brand_id')->where('lang_id', $lang);
    // }
    //
    // public function translationByLanguage($lang = '1')
    // {
    //     return $this->hasOne(BrandTranslation::class)->where('lang_id', $lang);
    // }
}
