<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['category_id', 'set_id', 'promotion_id', 'alias', 'position', 'price', 'actual_price', 'discount', 'hit', 'recomended', 'stock', 'code', 'video'];

    public function translations()
    {
        return $this->hasMany(ProductTranslation::class);
    }

    public function category()
    {
        return $this->hasOne(ProductCategory::class, 'id', 'category_id');
    }

    public function translation($lang = 1)
    {
        return $this->hasMany(ProductTranslation::class)->where('lang_id', $lang);
    }

    public function translationByLanguage($lang)
    {
        return $this->hasOne(ProductTranslation::class)->where('lang_id', $lang);
    }

    public function setImage($setId)
    {
        $photo = $this->hasOne(SetProductImage::class, 'product_id')->where('set_id', $setId);

        return $photo;
    }

    public function setImages()
    {
        return  $this->hasMany(SetProductImage::class, 'product_id')->inRandomOrder();
    }

    public function brand()
    {
        return $this->hasOne(Brand::class, 'id', 'brand_id');
    }

    public function mainImage()
    {
        $photo = $this->hasOne(ProductImage::class, 'product_id')->where('main', 1);
        if (is_null($photo->first())) {
            $photo = $this->hasOne(ProductImage::class, 'product_id')->where('type', 'image');
        }
        return $photo;
    }

    public function withoutBack() {
        $photo = $this->hasOne(ProductImage::class, 'product_id')->where('background', 1);

        if (is_null($photo->first())) {
            $photo = $this->hasOne(ProductImage::class, 'product_id')->where('type', 'image');
        }
        return $photo;
    }

    public function image() {
        return $this->hasOne(ProductImage::class, 'product_id')->where('type', 'image')->where('main', 0)->where('background', 0);
    }

    public function images()
    {
         return $this->hasMany(ProductImage::class, 'product_id')->where('type', 'image')->where('main', 0)->orderBy('background', 'desc');
    }

    public function lifestyleImages() {
        return $this->hasMany(ProductImage::class, 'product_id')->where('type', 'lifestyleImage');
    }

    public function videos() {
        return $this->hasMany(ProductVideo::class, 'product_id');
    }

    public function inCart()
    {
        $user_id = auth('persons')->id() ? auth('persons')->id() : @$_COOKIE['user_id'];
        return $this->hasOne(Cart::class, 'product_id')->where('set_id', 0)->where('user_id', $user_id);
    }

    public function inWishList()
    {
        $user_id = auth('persons')->id() ? auth('persons')->id() : @$_COOKIE['user_id'];
        return $this->hasOne(WishList::class, 'product_id')->where('set_id', 0)->where('user_id', $user_id);
    }

    public function similar()
    {
      return $this->hasMany(ProductSimilar::class);
    }

    public function subproducts()
    {
      return $this->hasMany(SubProduct::class);
    }

    public function subproductById($id)
    {
      return $this->hasOne(SubProduct::class)->where('id', $id);
    }

    public function property()
    {
      return $this->hasMany(SubProductProperty::class, 'product_category_id', 'category_id');
    }

    public function cart()
    {
      return $this->hasOne(Cart::class, 'product_id', 'id');
    }

    public function set()
    {
        return $this->hasOne(Set::class, 'id', 'set_id');
    }

    public function setProduct()
    {
        return $this->hasOne(SetProducts::class, 'product_id', 'id');
    }

    public function sets()
    {
        return $this->belongsToMany(Set::class, 'set_product');
    }
}
