<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Event;

class Participation extends Model
{
    public static function boot()
    {
        parent::boot();

        static::creating(function ($participation) {
            Event::fire('participation_creating', $participation);
        });
    }

    public function promotion()
    {
        return $this->belongsTo('App\Promotion');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
