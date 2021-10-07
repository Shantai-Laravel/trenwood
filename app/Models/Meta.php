<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Meta extends Model
{
    public function translations()
    {
        return $this->hasMany(MetaTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(MetaTranslation::class)->where('lang_id', $lang);
    }
}
