<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

class User extends Authenticatable
{
    use SyncableGraphNodeTrait;

    protected static $graph_node_field_aliases = [
        'location.id' => 'location_id',
        'location.name' => 'location_name'
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
