<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleSubMenuTranslation extends Model
{
    protected $table = 'modules_submenu_translation';

    protected $fillable = ['module_id', 'lang_id', 'name', 'description'];

    public function submodule() {
        return $this->belongsTo(ModuleSubMenu::class);
    }
}
