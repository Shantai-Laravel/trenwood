<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSimilar extends Model
{
  protected $table = 'products_similar';

    protected $fillable = ['category_id'];
}
