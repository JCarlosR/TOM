<?php

namespace App\Http\Controllers\Creator;


use Cloudinary;
use Cloudinary\Uploader;

class PostCloudinaryFile
{
    
    public static function upload($file, $type)
    {
        Cloudinary::config([
            "cloud_name" => "tombofans",
            "api_key" => env('CLOUDINARY_API_KEY'),
            "api_secret" => env('CLOUDINARY_API_SECRET')
        ]);

        $userId = auth()->id();

        $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '-' . uniqid();
        $fileName = str_slug($fileName); // remove invalid characters :D

        $cloudinary_public_id = "posts/user-$userId/$fileName"; // should not start with /

        return Uploader::upload($file, [
            "public_id" => $cloudinary_public_id,
            "timeout" => 3600,
            "resource_type" => $type
        ]);
    }
    
}