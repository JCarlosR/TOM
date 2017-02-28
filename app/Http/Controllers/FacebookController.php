<?php

namespace App\Http\Controllers;

use App\User;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookController extends Controller
{

    public function login(LaravelFacebookSdk $fb)
    {
        // Array of permissions
        $login_url = $fb->getLoginUrl(['email', 'user_location', 'manage_pages']);
        return redirect($login_url);
    }

    public function callback(LaravelFacebookSdk $fb)
    {
        // Obtain an access token
        try {
            $token = $fb->getAccessTokenFromRedirect();
        } catch (FacebookSDKException $e) {
            die($e->getMessage());
        }

        // Token will be null if the user denied the request
        if (! $token) {
            $helper = $fb->getRedirectLoginHelper();
            if (! $helper->getError()) {
                abort(403, 'Unauthorized action.');
            }

            // If the promotion param exists redirect to the proper TOM page
            $promotion_id = session()->get('promotion_id');
            if ($promotion_id) {
                session()->put('promotion_id', '');
                return redirect('/promotion/'.$promotion_id);
            } else {
                return redirect('/');
            }
        }

        if (! $token->isLongLived()) {
            // OAuth 2.0 client handler
            $oauth_client = $fb->getOAuth2Client();

            // Extend the access token.
            try {
                $token = $oauth_client->getLongLivedAccessToken($token);
            } catch (FacebookSDKException $e) {
                dd($e->getMessage());
            }
        }

        $fb->setDefaultAccessToken($token);

        // Save for later
        session()->put('fb_user_access_token', (string) $token);

        // Get basic user info from Facebook
        try {
            $response = $fb->get('/me?fields=id,name,email,location');
        } catch (FacebookSDKException $e) {
            die($e->getMessage());
        }

        // If the user is a participant, this variable allows the redirect
        $promotion_id = session()->get('promotion_id');

        // Convert the response to a `Facebook/GraphNodes/GraphUser` collection
        $facebook_user = $response->getGraphUser();

        // Create the user if it does not exist or update the existing
        // This will only work if you've added the SyncableGraphNodeTrait to your User model
        $user = User::createOrUpdateGraphNode($facebook_user);

        if (!$user->welcome_mail_sent && !$promotion_id) // !promotion_id => logged as creator
            $user->setAsCreator();

        // Log the user into Laravel
        Auth::login($user);

        // If the promotion param exists redirect to the proper TOM page
        if ($promotion_id) {
            // clear to avoid future redirects
            session()->put('promotion_id', '');
            return redirect('/promotion/'.$promotion_id);
        } else {
            // it is a creator

            // Update referral ID when null to
            // 1: if there is no a referral, the first user represents an empty value
            // X: when there is a user id in the affiliate_to session variable
            if (! $user->referred_by) {
                $affiliate_to = session()->get('affiliate_to', 1);
                $user->referred_by = $affiliate_to;
                $user->save();

                session()->forget('affiliate_to');
            }
        }

        // Redirect creators to panel or tutorial (first time)
        if (auth()->user()->show_tutorial)
            return redirect('/tutorial');
        else
            return redirect('/home')->with('message', 'Has iniciado sesión correctamente con Facebook !');
    }
}
