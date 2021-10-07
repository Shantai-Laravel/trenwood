<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'address_id', 'is_logged', 'amount', 'status', 'secondarystatus', 'paymentstatus', 'delivery', 'payment', 'datetime', 'promocode_id'];

    public function orderProducts() {
        return $this->hasMany(OrderProduct::class);
    }

    public function orderProductsNoSet() {
        return $this->hasMany(OrderProduct::class)->where('set_id', 0);
    }

    public function orderSets() {
        return $this->hasMany(OrderSet::class);
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

    public function promocode() {
        return $this->hasOne(Promocode::class, 'id', 'promocode_id');
    }
}
