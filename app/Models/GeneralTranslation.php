<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralTranslation extends Model
{
    protected $table = 'general_translation';

    protected $fillable = ['name', 'body', 'description', 'lang_id'];
}
