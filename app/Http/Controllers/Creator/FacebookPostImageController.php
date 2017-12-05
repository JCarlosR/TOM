<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\ScheduledPostImage;
use Cloudinary;
use Illuminate\Http\Request;

use App\Http\Requests;

class FacebookPostImageController extends Controller
{

    public function store(Request $request)
    {
        $file = $request->file('file');
        $response = PostCloudinaryFile::upload($file, 'image');

        $image = new ScheduledPostImage();
        $image->user_id = auth()->id();
        $image->cloudinary_public_id = $response['public_id'];
        $image->secure_url = $response['secure_url'];
        $image->save();

        return $image;
    }
}
