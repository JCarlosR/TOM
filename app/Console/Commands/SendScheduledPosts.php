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

    // fan page access token (the admin access token is taken from the fan page)
    private $fanPageAccessToken;

    private $targetPageId = '2079845645561063';
    private $targetGroupId = '948507005305322';
    private $targetFbId; // currently selected target

    public function __construct(LaravelFacebookSdk $facebookSdk)
    {
        parent::__construct();

        $this->facebookSdk = $facebookSdk;
        $this->fanPageAccessToken = env('FAN_PAGE_TARGET_ACCESS_TOKEN');
    }

    public function shouldPostTo($targetType, ScheduledPost $post) {
        $scheduled_date_time = new Carbon($post->scheduled_date. ' ' . $post->scheduled_time);

        if ($targetType == 'page')
            $scheduled_date_time->addMinutes(7);

        $now = Carbon::now();
        // dd($scheduled_date_time);
        // dd($now->diffInMinutes($scheduled_date_time));
        return $now->diffInMinutes($scheduled_date_time) <= 1;
    }

    public function handle() // TO DO: Use queries to get directly the posts that should be posted
    {
        // Post to group the pending posts
        $scheduled_posts = ScheduledPost::where('status', 'Pendiente')->get();

        foreach ($scheduled_posts as $post) {
            if ($this->shouldPostTo('group', $post))
                $this->postToFacebook($post, 'group');
        }

        // Post to group the immediately posts
        $scheduled_posts = ScheduledPost::where('status', 'En cola')->get();
        foreach ($scheduled_posts as $post)
            $this->postToFacebook($post, 'group');

        // Post to fan-page 7 minutes later
        $awaiting_posts = ScheduledPost::where('status', 'Enviado')
            ->whereNull('published_to_fan_page_at')->get();
        foreach ($awaiting_posts as $post)
            if ($this->shouldPostTo('page', $post))
                $this->postToFacebook($post, 'page');
    }

    public function setFbAccessTokenAndTargetId($type)
    {
        if ($type == 'group') {
            // For group use the admin account (fb user access token)
            $user = User::where('email', 'vdesconocido777@gmail.com')->first(['fb_access_token']); // User::where('id', $post->user_id)->first(['fb_access_token']);
            if (!$user) return; // user not found

            $this->facebookSdk->setDefaultAccessToken($user->fb_access_token);
            $this->targetFbId = $this->targetGroupId;
        } elseif ($type == 'page') {
            // use the fan page access token
            $this->facebookSdk->setDefaultAccessToken($this->fanPageAccessToken);
            $this->targetFbId = $this->targetPageId;
        }
    }

    public function postToFacebook(ScheduledPost $post, $targetType)
    {
        $this->setFbAccessTokenAndTargetId($targetType);

        // the strategy to post can be the same for groups or pages
        // it only varies for the images post
        if ($post->type == 'link') {
            $status = $this->postLink($post);
        } elseif ($post->type == 'text') {
            $status = $this->postText($post);
        } elseif ($post->type == 'image') {
            $status = $this->postPhotoStory($post);
        } elseif ($post->type == 'images') {
            if ($targetType == 'group')
                $status = $this->postPhotosAndStory($post); // groups feed
            elseif ($targetType == 'page')
                $status = $this->postAlbum($post); // fan pages feed
        } elseif ($post->type == 'video') {
            $status = $this->postVideo($post);
        }

        if ($targetType == 'group')
            $post->status = $status; // 3 states
        elseif ($targetType == 'page')
            $post->published_to_fan_page_at = Carbon::now(); // mark as published

        $post->save();
    }


    public function postLink(ScheduledPost $post)
    {
        // Post to a fb group or fan page
        $queryUrl = "/$this->targetFbId/feed";
        $params = [
            'message' => $post->description,
            'link' => $post->link,
            'message_tags' => [
                [
                    'id' => '135645013606256',
                    'name' => 'Victor Velasco',
                    'type' => 'user'
                ]
            ]
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postLink (SDK Error)', $e->getMessage());
            return 'Error';
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
        // Post to a fb group or fan page
        $queryUrl = "/$this->targetFbId/feed";
        $params = [
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postText (SDK Error)', $e->getMessage());
            return 'Error';
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
        // Post to a fb group or fan page
        $queryUrl = "/$this->targetFbId/photos";
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
        $queryUrl = "/$this->targetFbId/photos";
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
        // Post to a fb group or fan page
        $queryUrl = "/$this->targetFbId/feed";
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
        // Upload a photo into a group / page
        $queryUrl = "/$this->targetFbId/videos";
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
            $description = "Post $postId: $method ejecutado correctamente sobre $this->targetFbId:";
        else
            $description = "Post $postId: $method ejecutado con error sobre $this->targetFbId:";
        $this->info($description);

        $this->info($message);
    }

    // there is no way to associate a post with an album (for groups feed)
    // but it works for fan-pages (and the multi photo strategy doesn't work for pages)

    public function postAlbum(ScheduledPost $post)
    {
        // Upload a photo to a fan page
        $queryUrl = "/$this->targetFbId/albums";
        $params = [
            'name' => 'Publicación TOM #' . $post->id,
            'message' => $post->description
        ];

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $albumId = $graphNode->getField('id');
            $this->prettyPrint($post->id, true, 'postAlbum', $graphNode->asJson());

            return $this->postAlbumPhotos($albumId, $post);
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'postAlbum', $e->getMessage());

            return "Error";
        }
    }

    private function postAlbumPhotos($albumId, ScheduledPost $post)
    {
        // Upload photos into an album
        $photosUrl = $post->images()->pluck('secure_url');

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
