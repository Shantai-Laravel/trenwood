<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $table = 'product_images';

    protected $fillable = ['product_id', 'type', 'src', 'main', 'background'];

    public function translations() {
        return $this->hasMany(ProductImageTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ProductImageTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(ProductImageTranslation::class, 'product_image_id')->where('lang_id', $lang);
    }
}
