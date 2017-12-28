<?php

namespace App\Http\Controllers\Participant;

use App\Http\Controllers\Controller;
use App\Participation;
use App\Promotion;
use App\User;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Support\Facades\Mail;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PromotionController extends Controller
{
    public function show($id, LaravelFacebookSdk $fb)
    {
        $promotion = Promotion::find($id);
        if (! $promotion) {
            $notification = 'La promoción a la que intentaste acceder no existe.';
            return redirect()->route('cuponera')->with(compact('notification'));
        }
        if ($promotion->end_date < date('Y-m-d')) {
            $notification = 'La promoción a la que intentaste acceder ha caducado.';
            return redirect()->route('cuponera')->with(compact('notification'));
        }


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

        /*
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
        */

        // Is the user authenticated?
        if (! auth()->check())
            return redirect("/facebook/promotion/$id");
        // Show a counter for the participations
        $userId = auth()->user()->id;
        $participationsCount = Participation::where('user_id', $userId)->where('promotion_id', $id)->count();

        return view('participant.promotion.show')->with(compact(
            'participantName', 'participantPicture',
            'promotion', 'participationsCount', 'locationId', 'fanPageName', 'fanPageFbId'
        ));
    }

    public function requestFbPermissions($id, LaravelFacebookSdk $fb)
    {
        // Get the appropriate promotion or redirect
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        session()->put('promotion_id', $promotion->id);

        $loginLink = $fb->getLoginUrl(['email', 'user_location']);

        $htmlResponse = '<meta property="og:type" content="article" />
                        <meta property="og:title" content="' . $promotion->fanPage->name . '" />
                        <meta property="og:description" content="' . $promotion->description . '" />';

        $htmlResponse .= '<meta property="og:image" content="' . asset('/images/promotions/'.$promotion->image_path) . '" />';

        $htmlResponse .= "<script>" .
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

        $fanPage = $promotion->fanPage;

        /*
        // The user has liked the page?

        // Fan page associated with the promotion
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

        // There creator has still available credits for the promotion?
        $creator = $fanPage->user;
        if ($creator->remaining_participations <= 0) {
            $data['success'] = false;
            $data['error_type'] = 'invalid_promotion';
            // Contact information of the user
            $data['name'] = $creator->name;
            $data['email'] = $creator->email;
            $this->sendNoCreditsMail($creator, false);
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

        // Consume 1 credit
        $creator->remaining_participations -= 1;
        $creator->save();

        if ($creator->remaining_participations == 0)
            $this->sendNoCreditsMail($creator, true);

        $data['success'] = true;
        $data['participation'] = $participation;
        return $data;
    }

    public function sendNoCreditsMail(User $user, $firstTime) {
        $data['user'] = $user;

        if ($firstTime) { // 0 credits from now
            Mail::send('emails.user_end_free_credits', $data, function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('Sus participaciones se han agotado');
            });
        } else { // impossible participations
            Mail::send('emails.no_credits_available_for_promotion', $data, function ($m) use ($user) {
                $m->to($user->email, $user->name)->subject('A 1 persona le interesó tu promoción pero no la pudo ver porque no has renovado tu suscripción');
            });
        }
    }
}
