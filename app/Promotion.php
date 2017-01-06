<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promotion extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $fillable = ['fan_page_id', 'description', 'end_date', 'image', 'attempts'];

    // accessors

    public function getImagePathAttribute()
    {
        return $this->id . '.' . $this->image;
    }

    public function getLastTicketAttribute()
    {
        return $this->participations->count();
    }

    public function getCityAttribute()
    {
        return $this->fanPage->user->location_name;
    }

    // relationships

    public function fanPage()
    {
        return $this->belongsTo('App\FanPage');
    }

    public function participations()
    {
        return $this->hasMany('App\Participation');
    }

}
