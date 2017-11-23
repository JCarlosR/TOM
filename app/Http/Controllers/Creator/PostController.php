<?php

namespace App\Http\Controllers\Creator;

use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PostController extends Controller
{
    public function grantPermissions(LaravelFacebookSdk $fb)
    {
        $permissions = [
            'publish_actions', 'user_managed_groups'
        ];

        $login_url = $fb->getRedirectLoginHelper()
            ->getLoginUrl(url('admin/posts/callback'), $permissions);

        return redirect($login_url);
    }

    public function test(LaravelFacebookSdk $fb)
    {
        $token = session('fb_user_access_token');
        dd($token);
        $fb->setDefaultAccessToken($token);
        $queryUrl = '/344343375954777/feed';
        $params = [
            'message' => 'Testing from php sdk',
            'status_type' => 'created_note',
            'link' => 'www.tombofans.com',
            'picture' => 'http://static.tibia.com/images/news/inspect_characterbig.png',
            'caption' => 'caption',
            'description' => 'description',
            'name' => 'name of the link'
        ];
        try {
            $response = $fb->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            die($e->getMessage());
        }

        $graphNode = $response->getGraphNode();
        dd($graphNode);
    }
}
