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

    // promotions count

    public function promotionsCountRelation()
    {   // hack to count with good performance
        return $this->hasOne('App\Promotion') // used just to get the value instead of a collection
        ->selectRaw('count(1) as aggregate')
            ->groupBy('fan_page_id');
    }

    public function getPromotionsCountAttribute()
    {
        return $this->promotionsCountRelation ? $this->promotionsCountRelation->aggregate : 0;
    }
}
