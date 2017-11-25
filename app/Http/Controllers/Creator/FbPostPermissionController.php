<?php

namespace App\Http\Controllers\Creator;

use Carbon\Carbon;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FbPostPermissionController extends Controller
{
    public function grant(LaravelFacebookSdk $fb)
    {
        // dd(url('admin/posts/callback')); https is here!
        $permissions = [
            'publish_actions', 'user_managed_groups'
        ];

        $loginUrl = $fb->getLoginUrl($permissions, url('/facebook/posts/callback'));
        return redirect($loginUrl);
    }

    public function callback(LaravelFacebookSdk $facebookSdk)
    {
        $this->getLongLivedAccessToken($facebookSdk);
        return redirect('/facebook/posts');
    }

    public function getLongLivedAccessToken(LaravelFacebookSdk $fb)
    {
        // Obtain an access token
        $token = $fb->getAccessTokenFromRedirect(url('/facebook/posts/callback'));

        // Access token will be null if the user denied the request
        // or if someone just hit this URL outside of the OAuth flow
        if (! $token) {
            // Get the redirect helper
            $helper = $fb->getRedirectLoginHelper();

            if (! $helper->getError()) {
                abort(403, 'Unauthorized action.');
            }

            // User denied the request
            dd(
                $helper->getError(),
                $helper->getErrorCode(),
                $helper->getErrorReason(),
                $helper->getErrorDescription()
            );
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
        session()->put('fb_user_access_token', (string) $token);
        auth()->user()->update([
            'fb_access_token' => $token,
            'fb_access_token_updated_at' => Carbon::now()
        ]);
    }
}
