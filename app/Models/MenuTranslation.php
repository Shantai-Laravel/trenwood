<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class MenuTranslation extends Model
{
    protected $table = 'menus_translation';

    protected $fillable = ['lang_id', 'name', 'url'];

    public function menu() {

        return $this->belongsTo(Menu::class);
    }
}
