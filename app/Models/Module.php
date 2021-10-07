<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Lang;

class Module extends Model
{
    protected $fillable = [
        'src', 'controller', 'position', 'table_name', 'icon', 'parent_id'
    ];

    public function submenu()
    {
        return $this->hasMany(ModuleSubMenu::class);
    }

    public function translations()
    {
        return $this->hasMany(ModuleTranslation::class);
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ModuleTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLang($langId = 1)
    {
        return $this->hasMany(ModuleTranslation::class)->where('lang_id', $langId);
    }

    public function parent()
    {
        return $this->belongsTo(Module::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Module::class, 'parent_id');
    }
}
