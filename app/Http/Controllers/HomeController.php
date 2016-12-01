<?php

namespace App\Http\Controllers;

use App\FanPage;
use App\Http\Requests;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Session;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(LaravelFacebookSdk $fb)
    {
        // If a token is not provided, I get this message:
        // "You must provide an access token"
        $token = session('fb_user_access_token');
        $fb->setDefaultAccessToken($token);

        // Get fan pages (insert when no exist)
        // TODO: what happens when a fan page is deleted from facebook?
        try {
            $response = $fb->get('/me/accounts?fields=id,name,category');
        } catch (FacebookSDKException $e) {
            dd($e->getMessage());
        }
        $graphEdge = $response->getGraphEdge();
        $fanPagesArray = $graphEdge->asArray();
        $fanPages = new Collection();
        foreach ($fanPagesArray as $item) {
            $fanPage = FanPage::firstOrCreate([
                'fan_page_id' => $item['id'],
                'user_id' => auth()->user()->id,
                'name' => $item['name'],
                'category' => $item['category']
            ]);

            $fanPages->add($fanPage);
        }

        return view('panel.home')->with(compact('fanPages'));
    }

    public function config()
    {
        $fanPages = auth()->user()->fanPages;
        return view('panel.config')->with(compact('fanPages'));
    }
}
