<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyValue extends Model
{
    protected $table = 'property_values';

    protected $fillable = ['property_id', 'product_id', 'value_id'];

    public function translations() {
        return $this->hasMany(PropertyValueTranslation::class, 'property_value_id');
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(PropertyValueTranslation::class, 'property_values_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(PropertyValueTranslation::class, 'property_values_id')->where('lang_id', $lang);
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(PropertyValueTranslation::class, 'property_values_id')->where('lang_id', $lang);
    }
}
