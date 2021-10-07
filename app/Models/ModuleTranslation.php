<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleTranslation extends Model
{
    protected $table = 'modules_translation';

    protected $fillable = ['model_id', 'lang_id', 'name', 'description'];

    public function module() {
        return $this->hasMany(Module::class);
    }
}
