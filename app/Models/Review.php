<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $table = 'reviews';

    protected $fillable = ['alias', 'img'];

    public function translations() {
        return $this->hasMany(ReviewTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ReviewTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(ReviewTranslation::class)->where('lang_id', $lang);
    }
}
