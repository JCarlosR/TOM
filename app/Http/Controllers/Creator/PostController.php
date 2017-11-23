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

    public function test(LaravelFacebookSdk $facebookSdk)
    {
        $this->getLongLivedAccessToken($facebookSdk);

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
            $response = $facebookSdk->post($queryUrl, $params);
        } catch (FacebookSDKException $e) {
            print_r('* Catch FacebookSDKException exception');
            die($e->getMessage());
        }

        $graphNode = $response->getGraphNode();
        dd($graphNode);
    }

    public function getLongLivedAccessToken(LaravelFacebookSdk $fb)
    {
        // Obtain an access token
        try {
            $token = $fb->getAccessTokenFromRedirect();
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

        $token = session('fb_user_access_token');
        $fb->setDefaultAccessToken($token);
    }
}
