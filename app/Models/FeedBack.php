<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $table = 'feed_back';

    protected $fillable = ['form', 'first_name', 'second_name', 'email', 'phone', 'subject', 'message', 'additional_1', 'additional_2', 'additional_3', 'status'];

}
