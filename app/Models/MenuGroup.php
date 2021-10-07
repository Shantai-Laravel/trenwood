<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuGroup extends Model
{
    protected $table = 'menus_groups';

    protected $fillable = ['name', 'slug'];

}
