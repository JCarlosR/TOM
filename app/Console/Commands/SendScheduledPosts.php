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
        } elseif ($post->type == 'image') {
            $status = $this->postPhoto($post);
        } elseif ($post->type == 'images') {
            $status = $this->postAlbum($post);
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
            $this->info("postLink ejecutado correctamente para el post $post->id:");
            $this->info($graphNode->asJson());
            $this->info("---");

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->info("postLink ejecutado con error para el post $post->id:");
            $this->info($e->getMessage());
            $this->info("---");

            return "Error";
        }
    }

    public function postPhoto(ScheduledPost $post)
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
            $this->info("postPhoto ejecutado correctamente para el post $post->id:");
            $this->info($graphNode->asJson());
            $this->info("---");

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->info("postPhoto ejecutado con error para el post $post->id:");
            $this->info($e->getMessage());
            $this->info("---");

            return "Error";
        }
    }

    public function postAlbum(ScheduledPost $post)
    {
        // Upload a photo into a group
        $groupId = $post->fb_destination_id;
        $queryUrl = "/$groupId/albums";
        $params = [
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $this->info("postAlbum ejecutado correctamente para el post $post->id:");
            $this->info($graphNode->asJson());
            $this->info("---");

            return $this->postAlbumPhotos();
        } catch (FacebookSDKException $e) {
            $this->info("postAlbum ejecutado con error para el post $post->id:");
            $this->info($e->getMessage());
            $this->info("---");

            return "Error";
        }
    }

    private function postAlbumPhotos()
    {
        return "Enviado";
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
            $this->info("postVideo ejecutado correctamente para el post $post->id:");
            $this->info($graphNode->asJson());
            $this->info("---");

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->info("postVideo ejecutado con error para el post $post->id:");
            $this->info($e->getMessage());
            $this->info("---");

            return "Error";
        }
    }
}
