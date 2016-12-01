<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FanPage extends Model
{
    protected $fillable = ['fan_page_id', 'user_id', 'name', 'category'];
}
