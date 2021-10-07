<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSubMenu extends Model
{
    protected $table = 'modules_submenu';

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function translations()
    {
        return $this->hasMany(ModuleSubMenuTranslation::class, 'modules_submenu_id');
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(ModuleSubMenuTranslation::class, 'modules_submenu_id')->where('lang_id', $lang);
    }

}
