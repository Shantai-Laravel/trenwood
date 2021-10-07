<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menus';

    protected $fillable = ['parent_id', 'alias', 'level', 'position', 'group_id', 'icon', 'icon_hover'];

    public function translations() {
        return $this->hasMany(MenuTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(MenuTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasMany(MenuTranslation::class)->where('lang_id', $lang);
    }
}
