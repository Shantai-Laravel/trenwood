<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class ProductVideoTranslation extends Model
{
    protected $fillable = ['lang_id', 'src'];

    protected $table = 'product_videos_translation';

    public function video() {

        return $this->belongsTo(ProductVideo::class);
    }
}
