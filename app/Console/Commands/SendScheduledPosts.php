<?php

namespace App\Console\Commands;

use App\ScheduledPost;
use App\User;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Console\Command;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class SendScheduledPosts extends Command
{

    protected $signature = 'posts:send';
    protected $description = 'Send facebook scheduled posts';

    private $facebookSdk;

    public function __construct(LaravelFacebookSdk $facebookSdk)
    {
        parent::__construct();

        $this->facebookSdk = $facebookSdk;
    }

    public function handle()
    {
        // Get all pending posts
        $scheduled_posts = ScheduledPost::where('status', 'Pendiente')->get();
        // Send each one when needed
        foreach ($scheduled_posts as $post) {
            $schedule_date_time = new Carbon($post->scheduled_date. ' ' . $post->scheduled_time);
            // dd($schedule_date_time);
            $now = Carbon::now();
            // dd($now);
            // dd($now->diffInMinutes($schedule_date_time));
            if ($now->diffInMinutes($schedule_date_time) <= 1) {
                $this->postAndMarkAsSent($post);
            }
        }
    }

    public function postAndMarkAsSent(ScheduledPost $post)
    {
        // set access token
        $user = User::where('id', $post->user_id)->first(['fb_access_token']);
        if (!$user) return; // user not found (?)
        $this->facebookSdk->setDefaultAccessToken($user->fb_access_token);

        if ($post->type == 'link') {
            $status = $this->postLink($post);
        } elseif ($post->type == 'text') {
            $status = $this->postText($post);
        } elseif ($post->type == 'image') {
            $status = $this->postPhotoStory($post);
        } elseif ($post->type == 'images') {
            $status = $this->postPhotosAndStory($post);
        } elseif ($post->type == 'video') {
            $status = $this->postVideo($post);
        }

        $post->status = $status;
        $post->save();
    }

    public function postLink(ScheduledPost $post)
    {
        // Post to a fb group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/feed";
        $params = [
            'message' => $post->description,
            'link' => $post->link
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            echo 'SDK Error: ' . $e->getMessage();
            exit;
        }

        try {
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'postLink', $graphNode->asJson());

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postLink', $e->getMessage());

            return "Error";
        }
    }

    public function postText(ScheduledPost $post)
    {
        // Post to a fb group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/feed";
        $params = [
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            echo 'SDK Error: ' . $e->getMessage();
            exit;
        }

        try {
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'postText', $graphNode->asJson());
            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postText', $e->getMessage());
            return "Error";
        }
    }

    public function postPhotoStory(ScheduledPost $post)
    {
        // Upload a photo into a group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/photos";
        $params = [
            'url' => $post->image_url,
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'postPhoto', $graphNode->asJson());

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postPhoto', $e->getMessage());

            return "Error";
        }
    }

    public function postPhotosAndStory(ScheduledPost $post)
    {
        // Upload unpublished photos
        /*$photosUrl = [
            'https://static.pexels.com/photos/605096/pexels-photo-605096.jpeg',
            'https://www.planwallpaper.com/static/images/b807c2282ab0a491bd5c5c1051c6d312_k4PiHxO.jpg',
            'http://hdwarena.com/wp-content/uploads/2017/04/Beautiful-Wallpaper.jpg'
        ];*/
        $photosUrl = $post->images()->pluck('secure_url');

        $photosId = [];
        foreach ($photosUrl as $photoUrl) {
            $photoId = $this->postUnpublishedPhoto($post, $photoUrl);
            if ($photoId)
                $photosId[] = $photoId;
            else
                break;
        }

        if (sizeof($photosId) == 0)
            return 'Error';

        // Create post
        $postId = $this->createSimplePost($post);
        if (!$postId)
            return 'Error';

        // Associate photos with post
        foreach ($photosId as $photoId) {
            $success = $this->associateUnpublishedPhotoWithPost($photoId, $postId);
            if (!$success)
                return 'Error';
        }

        return 'Enviado';
    }

    private function postUnpublishedPhoto(ScheduledPost $post, $photoUrl)
    {
        // Upload a photo into a group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/photos";
        $params = [
            'url' => $photoUrl,
            'published' => false
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'postUnpublishedPhoto', $graphNode->asJson());

            return $graphNode->getField('id');
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postUnpublishedPhoto', $e->getMessage());

            return null;
        }
    }

    public function createSimplePost(ScheduledPost $post)
    {
        // Post to a fb group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/feed";
        $params = [
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            $errorMessage = 'SDK Error: ' . $e->getMessage();
            $this->prettyPrint($post->id, false, 'createSimplePost', $errorMessage);
            return null;
        }

        try {
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'createSimplePost', $graphNode->asJson());

            return $graphNode->getField('id');
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'createSimplePost', $e->getMessage());

            return null;
        }
    }

    private function associateUnpublishedPhotoWithPost($photoId, $postId)
    {
        $queryUrl = "/$photoId";
        $params = [
            'target_post' => $postId
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            $errorMessage = 'SDK Error: ' . $e->getMessage();
            $this->prettyPrint($postId, false, 'associateUnpublishedPhotoWithPost', $errorMessage);
            return false;
        }

        try {
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($postId, true, 'associateUnpublishedPhotoWithPost', $graphNode->asJson());

            return $graphNode->getField('success');
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($postId, false, 'associateUnpublishedPhotoWithPost', $e->getMessage());

            return false;
        }
    }


    public function postVideo(ScheduledPost $post)
    {
        // Upload a photo into a group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/videos";
        $params = [
            'file_url' => $post->video_url,
            'description' => $post->description
            // 'title' => 'title of the video'
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'postVideo', $graphNode->asJson());

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postVideo', $e->getMessage());

            return "Error";
        }
    }


    // pretty print for debug purposes (check the output file at Kernel.php)

    public function prettyPrint($postId, $success, $method, $message)
    {
        $dateNow = Carbon::now();
        $this->info("-- $dateNow --");
        if ($success)
            $description = "Post $postId: $method ejecutado correctamente:";
        else
            $description = "Post $postId: $method ejecutado con error:";
        $this->info($description);

        $this->info($message);
    }

    // not in use, because there is no way to associate a post with an album

    public function postAlbum(ScheduledPost $post)
    {
        // Upload a photo into a group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/albums";
        $params = [
            'name' => 'Publicación TOM #' . $post->id,
            'message' => $post->description,
            'make_shared_album' => true // not documented property
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $albumId = $graphNode->getField('id');
            $this->prettyPrint($post->id, true, 'postAlbum', $graphNode->asJson());

            return $this->postAlbumPhotos($albumId);
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postAlbum', $e->getMessage());

            return "Error";
        }
    }

    private function postAlbumPhotos($albumId)
    {
        // Upload photos into an album
        $photosUrl = [
            'https://static.pexels.com/photos/605096/pexels-photo-605096.jpeg',
            'https://www.planwallpaper.com/static/images/b807c2282ab0a491bd5c5c1051c6d312_k4PiHxO.jpg',
            'http://hdwarena.com/wp-content/uploads/2017/04/Beautiful-Wallpaper.jpg'
        ];

        $status = "Error";
        foreach ($photosUrl as $photoUrl) {
            $status = $this->postAlbumPhoto($albumId, $photoUrl);
        }

        return $status;
    }

    private function postAlbumPhoto($albumId, $photoUrl)
    {
        // Upload 1 photo to a specific album
        $queryUrl = "/$albumId/photos";
        $params = [
            'url' => $photoUrl
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $albumId = $graphNode->getField('id');
            $this->info("postAlbumPhoto ejecutado correctamente para el album $albumId:");
            $this->info($graphNode->asJson());
            $this->info("---");

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->info("postAlbumPhoto ejecutado con error para el album $albumId:");
            $this->info($e->getMessage());
            $this->info("---");

            return "Error";
        }
    }

}
