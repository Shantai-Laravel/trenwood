<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'location_regions';

    public function cities()
    {
        return $this->hasMany(City::class, 'location_region_id', 'id');
    }
}
