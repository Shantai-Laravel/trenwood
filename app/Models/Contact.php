<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $table = 'contacts';

    protected $fillable = ['title'];

    public function translations() {
        return $this->hasMany(ContactTranslation::class);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasMany(ContactTranslation::class)->where('lang_id', $lang);
    }
}
