<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Set extends Model
{
    protected $table = 'sets';

    protected $fillable = [
        'collection_id', 'alias', 'price', 'discount', 'position', 'code'
    ];

    public function translations()
    {
        return $this->hasMany(SetTranslation::class);
    }

    public function translationByLanguage($lang = 1)
    {
        return $this->hasOne(SetTranslation::class, 'set_id')->where('lang_id', $lang);
    }

    public function setProduct($productId)
    {
        return $this->hasOne(SetProducts::class, 'set_id', 'id')->where('product_id', $productId);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'set_product');
    }

    public function galleryItems()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id');
    }

    public function photos()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id')->where('type', 'photo')->where('main', 0)->where('background', 0);
    }

    public function videos()
    {
        return $this->hasMany(SetGallery::class, 'set_id', 'id')->where('type', 'video');
    }

    public function video() {
        return $this->hasOne(SetGallery::class, 'set_id', 'id')->where('type', 'video');
    }

    public function photo() {
        return $this->hasOne(SetGallery::class, 'set_id')->where('type', 'photo')->where('main', 0)->where('background', 0);
    }

    public function mainPhoto()
    {
        $photo = $this->hasOne(SetGallery::class, 'set_id')->where('type', 'photo')->where('main', 1);
        if (is_null($photo->first())) {
            $photo = $this->hasOne(SetGallery::class, 'set_id')->where('type', 'photo');
        }
        return $photo;
    }

    public function withoutBack() {
        $photo = $this->hasOne(SetGallery::class, 'set_id')->where('type', 'photo')->where('background', 1);

        if (is_null($photo->first())) {
            $photo = $this->hasOne(SetGallery::class, 'set_id')->where('type', 'photo');
        }
        return $photo;
    }

    public function collection()
    {
        return $this->hasOne(Collection::class, 'id', 'collection_id');
    }

    public function inCart()
    {
        $user_id = auth('persons')->id() ? auth('persons')->id() : @$_COOKIE['user_id'];
        return $this->hasOne(CartSet::class, 'set_id')->where('user_id', $user_id);
    }

    public function inWishList()
    {
        $user_id = auth('persons')->id() ? auth('persons')->id() : @$_COOKIE['user_id'];
        return $this->hasOne(WishListSet::class, 'set_id')->where('user_id', $user_id);
    }
}
