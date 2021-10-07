<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PropertyGroup extends Model
{
    protected $table = 'properties_groups';

    public function translations() {
        return $this->hasMany(PropertyGroupTranslation::class, 'property_group_id');
    }

    public function properties() {
        return $this->hasMany(ProductProperty::class, 'group_id');
    }

    public function translation()
    {
        $lang = Lang::where('lang', session('applocale'))->first()->id ?? Lang::first()->id;

        return $this->hasMany(PropertyGroupTranslation::class, 'property_group_id')->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasMany(PropertyGroupTranslation::class, 'property_group_id')->where('lang_id', $lang);
    }
}
