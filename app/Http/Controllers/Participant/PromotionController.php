<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Participation;
use App\Promotion;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PromotionController extends Controller
{
    public function show($id, LaravelFacebookSdk $fb)
    {
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        // Request permissions if the user still have not authenticated
        $token = session('fb_user_access_token');
        if (! $token)
            return redirect("/facebook/promotion/$id");

        // Fan page associated with the promotion
        $fanPage = $promotion->fanPage;
        $locationId = $fanPage->user->location_id;
        $fanPageName = $fanPage->name;
        $fanPageFbId = $fanPage->fan_page_id;

        // Use token to perform the queries
        $fb->setDefaultAccessToken($token);

        // Authenticated user info
        $query = "/me?fields=picture,name";
        try {
            $response = $fb->get($query);
        } catch (FacebookSDKException $e) {
            // It happens when the user revokes the permissions
            return redirect("/facebook/promotion/$id");
        }
        $graphNode = $response->getGraphNode();
        $participantName = $graphNode->getField('name');
        $participantPicture = $graphNode->getField('picture')['url'];

        // The user has liked the page?
        $query = "/me/likes/$fanPageFbId";
        try {
            $response = $fb->get($query);
        } catch (FacebookSDKException $e) {
            // Generally it happens when the user revoke the permissions after generate an access token
            return redirect("/facebook/promotion/$id");
        }
        $graphEdge = $response->getGraphEdge();
        $pageIsLiked = $graphEdge->count() > 0; // 1 when the page is liked

        // Is the user authenticated?
        if (! auth()->check())
            return redirect("/facebook/promotion/$id");
        // Show a counter for the participations
        $userId = auth()->user()->id;
        $participationsCount = Participation::where('user_id', $userId)->where('promotion_id', $id)->count();

        return view('participant.promotion.show')->with(compact(
            'participantName', 'participantPicture',
            'promotion', 'participationsCount', 'pageIsLiked', 'locationId', 'fanPageName', 'fanPageFbId'
        ));
    }

    public function requestFbPermissions($id, LaravelFacebookSdk $fb)
    {
        // Get the appropriate promotion or redirect
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        session()->put('promotion_id', $promotion->id);

        $loginLink = $fb->getLoginUrl(['email', 'user_location', 'user_likes']);

        $htmlResponse = "<script>" .
            "window.top.location = '$loginLink';" .
            "</script>";
        return $htmlResponse;
    }

    public function go($id, LaravelFacebookSdk $fb)
    {
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        $token = session('fb_user_access_token');
        if (! $token) {
            // The user needs to refresh the page
            $data['success'] = false;
            $data['error_type'] = 'token_has_expired';
            return $data;
        }

        /*
        // The user has liked the page?

        // Fan page associated with the promotion
        $fanPage = $promotion->fanPage;
        $fanPageFbId = $fanPage->fan_page_id;

        $fb->setDefaultAccessToken($token);
        $query = "/me/likes/$fanPageFbId";
        try {
            $response = $fb->get($query);
        } catch (FacebookSDKException $e) {
            // Generally it happens when the user revoke the permissions after generate an access token
            return redirect("/facebook/promotion/$id");
        }
        $graphEdge = $response->getGraphEdge();
        $pageIsLiked = $graphEdge->count() > 0; // 1 when the page is liked

        if (! $pageIsLiked) {
            $data['success'] = false;
            $data['error_type'] = 'not_liked';
            return $data;
        }
        */

        // There are valid credits in the promotion?
        // Free package only allows a max of 10 participations
        if ($promotion->participations->count() >= 10) {
            $data['success'] = false;
            $data['error_type'] = 'invalid_promotion';
            // Contact information of the user
            $responsible = $fanPage->user;
            $data['name'] = $responsible->name;
            $data['email'] = $responsible->email;
            return $data;
        }

        // Is the user authenticated?
        if (! auth()->check())
            return redirect("/facebook/promotion/$id");
        // Check last participation
        $userId = auth()->user()->id;
        $lastParticipation = Participation::where('user_id', $userId)->where('promotion_id', $id)
            ->orderBy('created_at', 'desc')->first(); // last
        if ($lastParticipation) {
            // Check if has passed 24 hours
            $now = Carbon::now();
            $after24hours = $lastParticipation->created_at->addDay(); // equivalent to ->addHours(24);
            if ($now->lt($after24hours)) {
                $data['success'] = false;
                $data['error_type'] = 'must_wait';
                return $data;
            }
        }

        // Register a new participation and check if is winner
        $participation = new Participation();
        $participation->user_id = $userId;
        $participation->promotion_id = $id;
        $participation->save();

        // a model event will fill the next fields with the proper values
        // $participation->ticket = ;
        // $participation->is_winner = ;

        $data['success'] = true;
        $data['participation'] = $participation;
        return $data;
    }
}
