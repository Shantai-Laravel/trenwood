<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PropertyValueTranslation extends Model
{
    protected $fillable = ['property_values_id', 'lang_id', 'value'];

    protected $table = 'property_values_translation';

    public function category() {
        return $this->belongsTo(Category::class);
    }
}
