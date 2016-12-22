<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FanPage extends Model
{
    protected $fillable = ['fan_page_id', 'user_id', 'name', 'category'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function promotions()
    {
        return $this->hasMany('App\Promotion');
    }
}
