<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $fillable = [
        'alias', 'banner', 'position'
    ];

    public function translations()
    {
        return $this->hasMany(CollectionTranslation::class);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasOne(CollectionTranslation::class, 'collection_id')->where('lang_id', $lang);
    }

    public function sets()
    {
        return $this->hasMany(Set::class)->orderBy('position', 'asc');
    }

    public function nieberSet($id)
    {
        $set = $this->hasOne(Set::class)->where('id', '!=', $id);

        if (is_null($set->first())) {
            return $this->hasOne(Set::class);
        }

        return $set;
    }

}
