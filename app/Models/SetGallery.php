<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SetGallery extends Model
{
    protected $table = 'sets_gallery';

    protected $fillable = [
        'set_id', 'type', 'src', 'home'
    ];

    public function translations()
    {
        return $this->hasMany(SetTranslation::class);
    }

    public function translation($lang = 1)
    {
        return $this->hasOne(SetTranslation::class, 'set_id')->where('lang_id', $lang);
    }
}
