<?php

namespace App\Http\Controllers;

use App\FanPage;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FanPageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id, LaravelFacebookSdk $fb)
    {
        $fanPage = FanPage::find($id);
        if (! $fanPage)
            return redirect('/config')->with('warning', 'No se ha encontrado la página buscada !');;

        if ($fanPage->user_id != auth()->user()->id)
            return redirect('/config')->with('warning', 'Evite acciones de este tipo !');

        if (! $fanPage->picture_200) {
            $query = '/'.$fanPage->fan_page_id.'/picture?redirect=false&width=200&height=200';

            $token = session('fb_user_access_token');
            $fb->setDefaultAccessToken($token);
            try {
                $response = $fb->get($query);
            } catch (FacebookSDKException $e) {
                die($e->getMessage());
            }
            $graphNode = $response->getGraphNode();
            $fanPage->picture_200 = $graphNode->getField('url');
            $fanPage->save();
        }

        return view('panel.fan_page')->with(compact('fanPage'));
    }

    public function promotions($id)
    {
        $fanPage = FanPage::find($id);
        $promotions = $fanPage->promotions;
        return view('panel.promotions')->with(compact('fanPage', 'promotions'));
    }
}
