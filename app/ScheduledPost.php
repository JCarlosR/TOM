<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(ScheduledPostImage::class);
    }

    public function getScheduledDateTime()
    {
        return new Carbon($this->scheduled_date. ' ' . $this->scheduled_time);
    }

    public static function existsCollisionsWith(ScheduledPost $post)
    {
        return ScheduledPost::where('scheduled_date', $post->scheduled_date)
            ->where('scheduled_time', $post->scheduled_time)->exists();
    }
}
