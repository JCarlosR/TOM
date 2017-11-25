<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Event;
use SammyK\LaravelFacebookSdk\SyncableGraphNodeTrait;

class User extends Authenticatable
{
    use SyncableGraphNodeTrait;

    public function setAsCreator()
    {
        Event::fire('creator_created', $this);
    }

    protected static $graph_node_field_aliases = [
        'id' => 'facebook_user_id',
        'location.id' => 'location_id',
        'location.name' => 'location_name'
    ];

    protected $fillable = [
        'name', 'email', 'password',
        'fb_access_token', 'fb_access_token_updated_at'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];


    // relationships

    public function fanPages()
    {
        return $this->hasMany(FanPage::class);
    }

    public function scheduledPosts()
    {
        return $this->hasMany(ScheduledPost::class);
    }

    // referrals count
    public function getReferralsCountAttribute()
    {
        return User::where('referred_by', $this->id)->count();
    }


    // Fan pages count

    public function fanPagesCountRelation()
    {   // hack to count with good performance
        return $this->hasOne('App\FanPage') // used just to get the value instead of a collection
            ->selectRaw('count(1) as aggregate')
            ->groupBy('user_id');
    }

    public function getFanPagesCountAttribute()
    {
        return $this->fanPagesCountRelation ? $this->fanPagesCountRelation->aggregate : 0;
    }


    // accessors
    public function getIsAdminAttribute()
    {
        $admin_emails = ['juancagb.17@hotmail.com', 'tombofans@gmail.com'];
        return in_array($this->email, $admin_emails);
    }

    public function getReferralLinkAttribute()
    {
        return '/invited-by/'.str_slug($this->name).'/'.$this->id;
    }
}
