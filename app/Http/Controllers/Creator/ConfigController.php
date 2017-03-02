<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Promotion;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Mail;
use Intervention\Image\Facades\Image;

class ConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function sendRightConfigurationMail(Promotion $promotion)
    {
        // creator
        $user = $promotion->fanPage->user;

        $data = [];
        $data['promotion'] = $promotion;
        $data['creator_name'] = $user->name;

        Mail::send('emails.right_configuration', $data, function ($m) use ($user) {
            $m->to($user->email, $user->name)->subject('Has creado correctamente tu promoción!');
        });
    }

    public function instructions($id)
    {
        $promotion = Promotion::find($id);
        if (! $promotion)
            return redirect('/');

        $promotionId = $id;
        $promotionSlug = str_slug($promotion->fanPage->name, '-');;
        $fanPageFbId = $promotion->fanPage->fan_page_id;

        return view('panel.promotion.instructions')->with(compact('promotionId', 'promotionSlug', 'fanPageFbId'));
    }
}
