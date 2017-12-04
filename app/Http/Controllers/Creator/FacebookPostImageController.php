<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\ScheduledPostImage;
use Cloudinary;
use Illuminate\Http\Request;

use App\Http\Requests;

class FacebookPostImageController extends Controller
{
    public function __construct()
    {
        Cloudinary::config(array(
            "cloud_name" => "tombofans",
            "api_key" => env('CLOUDINARY_API_KEY'),
            "api_secret" => env('CLOUDINARY_API_SECRET')
        ));
    }

    public function store(Request $request)
    {
        $userId = auth()->id();

        $file = $request->file('file');
        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid();
        $cloudinary_public_id = "posts/user-$userId/$fileName"; // should not start with /

        $response = Cloudinary\Uploader::upload($file, [
            "public_id" => $cloudinary_public_id
        ]);

        $image = new ScheduledPostImage();
        $image->user_id = $userId;
        $image->cloudinary_public_id = $response['public_id'];
        $image->secure_url = $response['secure_url'];
        $image->save();

        return $image;
    }
}
