<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected $fillable = ['alias', 'gallery_id', 'position', 'active'];

    public function gallery()
    {
        return $this->hasOne(Gallery::class, 'id', 'gallery_id');
    }

    public function translations()
    {
        return $this->hasMany(PageTranslation::class);
    }

    public function traductions()
    {
        return $this->hasMany(Traduction::class, 'page_id', 'id');
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(PageTranslation::class, 'page_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasMany(PageTranslation::class)->where('lang_id', $lang)->first();
    }

    public function translationByLang($lang = 1)
    {
        return $this->hasOne(PageTranslation::class)->where('lang_id', $lang);
    }
}
