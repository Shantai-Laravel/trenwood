<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReturSet extends Model
{
    protected $table = 'return_sets';
    protected $fillable = ['set_id', 'qty', 'price'];

    public function return() {
        return $this->hasOne(Retur::class, 'id', 'return_id');
    }

    public function returnProducts() {
        return $this->hasMany(ReturProduct::class, 'set_id', 'id');
    }

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }
}
