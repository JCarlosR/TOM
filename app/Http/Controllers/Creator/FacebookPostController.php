<?php

namespace App\Http\Controllers\Creator;

use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookPostController extends Controller
{

    public function create(LaravelFacebookSdk $facebookSdk)
    {
        try {
            $response = $facebookSdk->post('me/permissions');
            $graphNode = $response->getGraphNode();
            dd($graphNode);
        } catch (FacebookSDKException $e) {
            die($e->getMessage());
        }

        return view('panel.posts.create');
    }


}
