<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    public function images()
    {
        return $this->hasMany(ScheduledPostImage::class);
    }
}
