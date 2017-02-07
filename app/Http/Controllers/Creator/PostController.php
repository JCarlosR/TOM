<?php

namespace App\Http\Controllers\Creator;

use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PostController extends Controller
{
    public function test(LaravelFacebookSdk $fb)
    {

        $token = session('fb_user_access_token');
        $fb->setDefaultAccessToken($token);
        $queryUrl = '/344343375954777/feed';
        $params = [
            'message' => 'Testing from php sdk'
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
