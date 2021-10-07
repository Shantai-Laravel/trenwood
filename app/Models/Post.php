<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public function translations()
    {
        return $this->hasMany(PostTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(PostTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasOne(PostTranslation::class)->where('lang_id', $lang);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
