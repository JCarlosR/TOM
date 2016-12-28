<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

class User extends Authenticatable
{
    use SyncableGraphNodeTrait;

    public static function boot()
    {
        parent::boot();

        static::created(function ($user) {
            Event::fire('user_created', $user);
        });
    }

    protected static $graph_node_field_aliases = [
        'id' => 'facebook_user_id',
        'location.id' => 'location_id',
        'location.name' => 'location_name'
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function fanPages()
    {
        return $this->hasMany('App\FanPage');
    }
}
