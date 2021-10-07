<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retur extends Model
{
    protected $table = 'returns';
    protected $fillable = ['user_id', 'is_active', 'address_id', 'amount', 'status', 'delivery', 'payment', 'motive', 'datetime'];

    public function returnProducts() {
        return $this->hasMany(ReturProduct::class, 'return_id', 'id');
    }

    public function returnProductsNoSet() {
        return $this->hasMany(ReturProduct::class, 'return_id', 'id')->where('set_id', 0);
    }

    public function returnSets() {
        return $this->hasMany(ReturSet::class, 'return_id', 'id');
    }

    public function userLogged() {
        return $this->hasOne(FrontUser::class, 'id', 'user_id');
    }

    public function userUnlogged() {
        return $this->hasOne(FrontUserUnlogged::class, 'id', 'user_id');
    }

    public function addressById() {
        return $this->hasOne(FrontUserAddress::class, 'id', 'address_id');
    }

    public function addressPickupById() {
        return $this->hasOne(ContactTranslation::class, 'id', 'address_id');
    }
}
