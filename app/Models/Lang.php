<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lang extends Model
{
    protected $table = 'lang';
    protected $fillable = [
        'lang',
        'descr',
        'default_lang',
        'position',
        'active',
    ];

    public function bannerTop()
    {
        return $this->hasOne('\App\Models\BannerTop', 'lang_id', 'id');
    }
}
