<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductImageTranslation extends Model
{
    protected $fillable = ['lang_id', 'product_image_id', 'alt', 'title'];

    protected $table = 'product_images_translation';

    public function category() {

        return $this->belongsTo(ProductImage::class);
    }
}
