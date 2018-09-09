<?php

namespace App\Console\Commands;

use App\ScheduledPost;
use App\User;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use function foo\func;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class SendScheduledPosts extends Command
{

    protected $signature = 'posts:send';
    protected $description = 'Send facebook scheduled posts';

    private $facebookSdk;

    // fan page access token (the admin access token is taken from the fan page)
    private $fanPageAccessToken;

    private $destinationFbIds = [
        'club' => [
            'page' => '1567109470249042',
            'group' => '248133372209026'
        ],
        'test' => [
            'page' => '2079845645561063', // Página de prueba TOM
            'group' => '948507005305322' // Grupo Prueba TOM
        ]
    ];

    private $targetFbId; // currently selected target

    public function __construct(LaravelFacebookSdk $facebookSdk)
    {
        parent::__construct();
        $this->facebookSdk = $facebookSdk;
    }

    public function shouldPostTo($targetType, ScheduledPost $post) {
        $scheduled_date_time = $post->getScheduledDateTime();

        /*
        if ($targetType == 'page')
            $scheduled_date_time->addMinutes(7);
        */
        $now = Carbon::now();
        // Log::info($scheduled_date_time);
        // Log::info($now->diffInMinutes($scheduled_date_time));
        return $now->diffInMinutes($scheduled_date_time) <= 1;
    }

    public function handle() // TO DO: Use queries to get directly the posts that should be posted
    {
        // Post to group the pending posts
        $scheduled_posts = ScheduledPost::where('status', 'Pendiente')->get();

        $scheduled_posts->each(function ($post) {
            if ($this->shouldPostTo('group', $post))
                $this->postToFacebook($post, 'group');
        });

        // Post to group the immediately posts
        $scheduled_posts = ScheduledPost::where('status', 'En cola')->get();
        foreach ($scheduled_posts as $post)
            $this->postToFacebook($post, 'group');


        // Post to fan-page right now
        $awaiting_posts = ScheduledPost::whereIn('status', ['Pendiente', 'En cola'])
            ->whereNull('published_to_fan_page_at')->get();
        foreach ($awaiting_posts as $post)
            if ($this->shouldPostTo('page', $post))
                $this->postToFacebook($post, 'page');
    }

    public function setFbAccessTokenAndTargetId($type, User $user)
    {
        // target fb id
        if ($user->is_admin) {
            $this->targetFbId = $this->destinationFbIds['test'][$type];
        } else {
            $this->targetFbId = $this->destinationFbIds['club'][$type];
        }

        if ($type == 'group') {
            // use the fb user access token of an admin

            // the admins will post to a different group (test)
            if ($user->is_admin)
                $fbGroupAdmin = 'vdesconocido777@gmail.com';
            else
                $fbGroupAdmin = 'mamis@clubmomy.com';


            // User::where('id', $post->user_id)->first(['fb_access_token']);
            $user = User::where('email', $fbGroupAdmin)
                ->first(['fb_access_token']);

            if (!$user) return false; // user not found

            $this->facebookSdk->setDefaultAccessToken($user->fb_access_token);
        } elseif ($type == 'page') {
            // use the fan page access token

            // the token of the page is different for test and club
            if ($user->is_admin)
                $this->fanPageAccessToken = env('FAN_PAGE_TEST_ACCESS_TOKEN');
            else
                $this->fanPageAccessToken = env('FAN_PAGE_CLUB_ACCESS_TOKEN');

            $this->facebookSdk->setDefaultAccessToken($this->fanPageAccessToken);
        }

        return true;
    }

    public function postToFacebook(ScheduledPost $post, $targetType)
    {
        $successfulSetup = $this->setFbAccessTokenAndTargetId($targetType, $post->user);
        if (!$successfulSetup) {
            $this->prettyPrint($post->id, false, 'postToFacebook', 'No access token');
            return;
        }

        $status = $post->status; // avoid change for error situations

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
                $status = $this->postStoryAndPhotos($post); // groups feed
            elseif ($targetType == 'page')
                $status = $this->postAlbum($post); // fan pages feed
        } elseif ($post->type == 'video') {
            $status = $this->postVideo($post);
        }

        if ($status == 'Error') {
            // re integrate the discounted credit
            $user = $post->user;
            $user->remaining_participations = $user->remaining_participations +1;
            $user->save();
        } elseif ($status == 'Enviado' &&  $targetType == 'page') {
            // mark as published
            $post->published_to_fan_page_at = Carbon::now();
        }

        $post->status = $status; // 3 states
        $post->save();
    }


    public function postLink(ScheduledPost $post)
    {
        // Post to a fb group or fan page
        $queryUrl = "/$this->targetFbId/feed";
        $params = [
            'message' => $post->description,
            'link' => $post->link
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
            'target' => $postId
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
    // but it works for fan-pages (by other side, multi photo doesn't work for pages)

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

    // postPhotosAndStory stopped working: publish_actions is deprecated and doesn't allow to update the unpublished photos
    // let's try creating a story and uploading photos to it

    public function postStoryAndPhotos(ScheduledPost $post)
    {
        // Upload photos
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

        $postId = $this->createPostWithMediaPhotos($post, $photosId);
        if (!$postId)
            return 'Error';

        return 'Enviado';
    }

    public function createPostWithMediaPhotos(ScheduledPost $post, $photosId)
    {   // https://developers.facebook.com/docs/graph-api/photo-uploads
        $queryUrl = "/$this->targetFbId/feed";
        $params = [
            'message' => $post->description
        ];

        // add media photos
        foreach ($photosId as $key => $photoId) {
            $params['attached_media['.$key.']'] = '{"media_fbid":"'.$photoId.'"}';
        }

        try {
            $response = $this->facebookSdk->post($queryUrl, $params);
            $graphNode = $response->getGraphNode();
            $this->prettyPrint($post->id, true, 'createPostWithMediaPhotos', $graphNode->asJson());

            return "Enviado";
        } catch (FacebookSDKException $e) {
            $this->prettyPrint($post->id, false, 'createPostWithMediaPhotos', $e->getMessage());

            return "Error";
        }
    }
}
