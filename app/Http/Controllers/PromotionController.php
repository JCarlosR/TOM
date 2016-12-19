<?php

namespace App\Http\Controllers;

use App\Participation;
use App\Promotion;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class PromotionController extends Controller
{

    public function store(Request $request)
    {
        $rules = [
            'fan_page_id' => 'required|exists:fan_pages,id',
            'description' => 'required|max:180',
            'end_date' => 'date|after:tomorrow',
            'image' => 'required|image',
            'attempts' => 'required|min:1|max:10'
        ];
        $messages = [
            'fan_page_id.required' => 'Es necesario seleccionar una fanpage.',
            'fan_page_id.exists' => 'La fan page indicada no existe.',
            'description.required' => 'Olvidó ingresar una descripción',
            'description.max' => 'La descripción excede los 180 caracteres permitidos.',
            'end_date.date' => 'Ingrese una fecha válidad.',
            'end_date.after' => 'Ingrese una fecha posterior a mañana.',
            'image.required' => 'Debe subir una imagen para su promoción.',
            'image.image' => 'Solo se permiten subir imágenes.',
            'attempts.required' => 'Debe indicar la frecuencia con que se gana la promoción.',
            'attempts.min' => 'El número de veces para ganar debe ser al menos 1.',
            'attempts.max' => 'El número de veces para ganar debe ser 10 como máximo.'
        ];
        $this->validate($request, $rules, $messages);

        // TODO: Validate if the fan page is associated with the user

        $promotion = Promotion::create([
            'fan_page_id' => $request->get('fan_page_id'),
            'description' => $request->get('description'),
            'end_date' => $request->get('end_date'),
            'image' => '',
            'attempts' => $request->get('attempts')
        ]);

        // TODO: Use transactions

        $extension = $request->file('image')->getClientOriginalExtension();
        $file_name = $promotion->id . '.' . $extension;

        $path = public_path('images/promotions/' . $file_name);

        Image::make($request->file('image'))
            ->fit(1280, 720, function ($c) {
                $c->upsize(); // don't resize smaller images
            })
            ->save($path);

        $promotion->image = $extension;
        $promotion->save();

        return redirect('promotion/'.$promotion->id);
    }

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
        $fanPageFbId = $fanPage->fan_page_id;

        // The user has liked the page?
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

        // Is the user authenticated?
        if (! auth()->check())
            return redirect("/facebook/promotion/$id");
        // Show a counter for the participations
        $userId = auth()->user()->id;
        $participationsCount = Participation::where('user_id', $userId)->where('promotion_id', $id)->count();

        return view('promotion.show')->with(compact('promotion', 'participationsCount'));
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

        // Fan page associated with the promotion
        $fanPage = $promotion->fanPage;
        $fanPageFbId = $fanPage->fan_page_id;

        // The user has liked the page?
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
