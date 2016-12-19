<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = ['fan_page_id', 'description', 'end_date', 'image', 'attempts'];

    public function getImagePathAttribute()
    {
        return $this->id . '.' . $this->image;
    }

    public function fanPage()
    {
        return $this->belongsTo('App\FanPage');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

    public function getLastTicketAttribute()
    {
        return $this->participations->count();
    }
}
