<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $table = 'location_countries';

    public function regions()
    {
        return $this->hasMany(Region::class, 'location_country_id', 'id');
    }
}
