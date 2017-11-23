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
        // dd(url('admin/posts/callback')); https is here!
        $permissions = [
            'publish_actions', 'user_managed_groups'
        ];

        $loginUrl = $fb->getLoginUrl($permissions, url('/admin/posts/callback'));
        // dd($loginUrl);
        return redirect($loginUrl);
    }

    public function test(LaravelFacebookSdk $facebookSdk)
    {
        $this->getLongLivedAccessToken($facebookSdk);

        $queryUrl = '/344343375954777/feed';
        $params = [
            'message' => 'Testing from php sdk',
            'link' => 'www.tombofans.com'/*,
            'picture' => 'http://static.tibia.com/images/news/inspect_characterbig.png',
            'caption' => 'my caption',
            'description' => 'my description',
            'name' => 'name of my link'*/
        ];

        try {
            $response = $facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            var_dump($e->getTrace());
            die('** Catch FacebookSDKException exception');
        }

        $graphNode = $response->getGraphNode();
        dd($graphNode);
    }

    public function getLongLivedAccessToken(LaravelFacebookSdk $fb)
    {
        // Obtain an access token
        try {
            $token = $fb->getAccessTokenFromRedirect(url('/admin/posts/callback'));
        } catch (FacebookSDKException $e) {
            die($e->getMessage());
        }

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
    }
}
