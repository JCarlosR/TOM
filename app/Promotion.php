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

    // participations count
    public function participationsCountRelation()
    {   // hack to count with good performance
        return $this->hasOne('App\Participation') // used just to get the value instead of a collection
        ->selectRaw('count(1) as aggregate')
            ->groupBy('promotion_id');
    }

    public function getParticipationsCountAttribute()
    {
        return $this->participationsCountRelation ? $this->participationsCountRelation->aggregate : 0;
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
